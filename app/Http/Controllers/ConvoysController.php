<?php

namespace App\Http\Controllers;

use App\Convoy;
use App\DLC;
use App\Member;
use App\Tuning;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use TruckersMP\APIClient\Client;
use Woeler\DiscordPhp\Exception\DiscordInvalidResponseException;
use Woeler\DiscordPhp\Message\DiscordEmbedMessage;
use Woeler\DiscordPhp\Webhook\DiscordWebhook;

class ConvoysController extends Controller{

    public function view(Request $request, $all = false){
        if(!Auth::user()?->member) abort(404);
        $list = array();
        $convoys = Convoy::with('DLC', 'leadMember', 'leadMember.user', 'officialTruckTuning', 'officialTrailerTuning');
        if(Auth::user()->cant('viewAny', Convoy::class) || !$all){
            $operator = '<';
            if(Carbon::now()->format('H') >= '21') $operator = '<=';
            $convoys = $convoys->whereDate('start_time', $operator, Carbon::tomorrow())
                ->where('visible', '1')
                ->orWhere(function($query){
                    $query->where('start_time', '>', Carbon::now())
                        ->where('booked_by_id', Auth::user()->member->id);
                });
        }
        $convoys = $convoys->orderBy('start_time')->get();
        foreach($convoys as $convoy){
            $list[$convoy->start_time->format('Y-m-d')][] = $convoy;
        }
        $list = collect(array_reverse($list));
        return view('evoque.convoys.index', [
            'all' => $all,
            'convoys' => $list->forPage($request->input('page'), 8),
            'paginator' => new LengthAwarePaginator($list, count($list), 8, $request->input('page'), [
                'path' => url()->current(),
                'pageName' => 'page'
            ])
        ]);
    }

    public function public(){
        return view('convoys', [
            'convoy' => Convoy::where([
                ['visible', '=', '1'],
                ['public', '=', '1'],
                ['start_time', '>', Carbon::now()->subMinutes(90)->format('Y-m-d H:i')]
            ])->orderBy('start_time')->first()
        ]);
    }

    public function edit(Request $request, $id = null){
        $convoy = $id ? Convoy::with(['audits' => function($query){
            $query->limit(10)->orderBy('created_at', 'desc');
        }])->where('id', $id)->firstOrFail() : new Convoy();
        $id ? $this->authorize('updateOne', $convoy) : $this->authorize('create', Convoy::class);
        if($request->ajax() && $request->post()){
            $this->validate($request, $convoy->attributes_validation);
            $convoy->fill($request->post());
            $route_images = [];
            foreach(explode(',', $request->post('imageList')) as $image){
                if(is_file(public_path('images/convoys/'. $image))){
                    $route_images[] = $image;
                }else{
                    if(isset($request->file('route')[$image])){
                        $route_images[] = $convoy->saveImage($request->file('route')[$image]);
                    }
                }
            }
            $convoy->route = $route_images;
            if($request->hasFile('truck_image')) $convoy->truck_image = $convoy->saveImage($request->file('truck_image'));
            if($request->hasFile('trailer_image')) $convoy->trailer_image = $convoy->saveImage($request->file('trailer_image'));
            if($request->hasFile('alt_trailer_image')) $convoy->alt_trailer_image = $convoy->saveImage($request->file('alt_trailer_image'));
            $convoy->booking = !Auth::user()->can('update', Convoy::class);
            $convoy->start_time = Carbon::parse($request->input('start_date').' '.$request->input('start_time'))->format('Y-m-d H:i');
            $convoy->setTypeByTime();
            if($convoy->save()){
                $convoy->DLC()->sync($request->input('dlc'));
                return response()->json([
                    'redirect' => route('convoys.private'),
                    'message' => 'Конвой сохранён'
                ]);
            }else{
                return response()->json(['neok' => ['message' => 'Возникла ошибка']]);
            }
        }
        $servers = $convoy->defaultServers;
        if(!$id){
            $convoy->start_time = Carbon::now();
        }
        $booking = Auth::user()->cant('update', Convoy::class) && Auth::user()->can('updateOne', $convoy);
        return view('evoque.convoys.edit', [
            'booking' => $booking,
            'convoy' => $convoy,
            'servers' => $servers,
            'members' => $booking ? Member::where('id', Auth::user()->member->id)->get() : Member::with('role')->orderBy('nickname')->get(),
            'dlc' => DLC::orderBy('sort')->get()->groupBy('game'),
            'types' => $booking ? [$convoy->type => Convoy::$timesToType[$convoy->type]] : Convoy::$timesToType,
            'trucks_tuning' => Tuning::where(['type' => 'truck', 'visible' => '1'])->get()->groupBy('vendor'),
            'trailers_tuning' => Tuning::where(['type' => 'trailer', 'visible' => '1'])->get()->groupBy('vendor'),
        ]);
    }

    public function delete(Request $request, $id){
        $this->authorize('delete', Convoy::class);
        $convoy = Convoy::findOrFail($id);
        $convoy->deleteImages(public_path('/images/convoys/'));
        return $convoy->delete() ?
            redirect()->route('convoys.private', 'all')->with(['success' => 'Конвой успешно удалён!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function deleteImage(Request $request, $id){
        $convoy = Convoy::find($id);
        $id ? $this->authorize('updateOne', $convoy) : $this->authorize('create', Convoy::class);
        if($request->ajax()){
            try{
                $attr = $request->input('target');
                $convoy->$attr = null;
                $convoy->save();
            }catch(QueryException $e){
                return response()->json([
                    'status' => 'Не удалось удалить изображение из-за ошибки в БД...',
                    'message' => $e
                ], 400);
            }
            return response()->json([
                'status' => 'OK'
            ]);
        }
        return false;
    }

    public function toggle(Request $request, $id){
        $this->authorize('update', Convoy::class);
        $convoy = Convoy::findOrFail($id);
        $convoy->visible = !$convoy->visible;
        $convoy->booking = false;
        return $convoy->save() ?
            redirect()->route('convoys.private', 'all')->with(['success' => 'Конвой успешно отредактирован!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function toDiscord($convoy_id){
        $this->authorize('update', Convoy::class);
        $convoy = Convoy::findOrFail($convoy_id);
        $message = (new DiscordEmbedMessage())
            ->setTitle($convoy->title)
            ->setUrl(route('convoy.public'))
            ->setColor(14992641)
            ->setImage(Url::to('/').'/images/convoys/'.$convoy->route[0])
            ->setFooterText('ВТК EVOQUE');

        $message->addField('Дата', $convoy->start_time->isoFormat('LL'))
            ->addField('Место старта', $convoy->start_city.' - '. $convoy->start_company, true)
            ->addField('Место отдыха', $convoy->rest_city.' - '. $convoy->rest_company, true)
            ->addField('Место финиша', $convoy->finish_city.' - '. $convoy->finish_company, true)
            ->addField('Встречаемся на сервере', $convoy->server)
            ->addField('Начало конвоя', $convoy->start_time->subMinutes(30)->format('H:i') . ' по МСК', true)
            ->addField('Выезд с места старта', $convoy->start_time->format('H:i') . ' по МСК', true);
        $dlcs = [];
        foreach($convoy->DLC as $item){
            $dlcs[] = $item->title;
        }
        $message->addField($dlcs ? ':warning: Для участия требуется '.implode(', ', $dlcs) : '| ',
                "> *Выставив маршрут, ваша поездка будет комфортной и спокойной!*");
        $message->addField('Связь ведём через', $convoy->getCommunicationLink(), true)
            ->addField('Канал на сервере', 'Открытый конвой', true)
            ->addField("> *В колонне держим дистанцию не менее 70 метров по TAB и соблюдаем правила TruckersMP. Помимо рации, флуд, мат, а так же фоновая музыка запрещены и в голосовом канале Discord.*", '___');
        if(isset($convoy->truck) && $convoy->truck_public) $message->addField('Тягач', $convoy->truck);
        if(isset($convoy->trailer) && $convoy->trailer_public){
            $message->addField('Прицеп', $convoy->trailer, true);
            if(isset($convoy->cargo)) $message->addField('Груз', $convoy->cargo, true);
            if($convoy->trailer_image) $message->addField('> *Один груз — одна большая команда!*', '___');
        }
        $message->addField('Маршрут', Url::to('/').'/images/convoys/'.$convoy->route[0]);

        $webhook = new DiscordWebhook(env('DISCORD_PUBLIC_CONVOY_WEBHOOK_URL'));
        try{
            $webhook->send($message);
        }catch(DiscordInvalidResponseException $e){
            return redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return redirect()->back()->with(['success' => 'Конвой успешно запощен в Дискорде!']);
    }

    public function addCargoman(Request $request){
        if(!Auth::user()?->member) abort(404);
        $convoy = Convoy::findOrFail($request->input('id'));
        $this->validate($request, [
            'cargoman' => 'numeric|required|digits:6'
        ]);
        $convoy->cargoman = $request->input('cargoman');
        return $convoy->save() ?
            redirect()->route('convoys.private')->with(['success' => 'Код CargoMan успешно добавлен!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

}
