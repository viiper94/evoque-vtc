<?php

namespace App\Http\Controllers;

use App\Convoy;
use App\Member;
use Carbon\Carbon;
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
        $convoys = Convoy::with('leadMember', 'leadMember.user');
        if(Auth::user()->cant('viewAny', Convoy::class) || !$all){
            $operator = '<';
            if(Carbon::now()->format('H') >= '21') $operator = '<=';
            $convoys = $convoys->whereDate('start_time', $operator, Carbon::tomorrow())->where('visible', '1');
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

    public function add(Request $request){
        $this->authorize('create', Convoy::class);
        if($request->ajax() && $request->input('action') == 'remove_img'){
            return response()->json([
                'status' => 'OK'
            ]);
        }
        if($request->post()){
            $convoy = new Convoy();
            $this->validate($request, $convoy->attributes_validation);
            $convoy->fill($request->post());
            $convoy->visible = $request->input('visible') === 'on' ? 1 : 0;
            $convoy->public = $request->input('public') === 'on' ? 1 : 0;
            $convoy->truck_public = $request->input('truck_public') === 'on' ? 1 : 0;
            $convoy->trailer_public = $request->input('trailer_public') === 'on' ? 1 : 0;
            foreach($request->files as $key => $file){
                if($key === 'route' && is_array($file)){
                    $route_images = array();
                    foreach($file as $i => $route){
                        $route_images[] = $convoy->saveImage($route);
                    }
                    $convoy->route = $route_images;
                }else{
                    $convoy->$key = $convoy->saveImage($file);
                }
            }
            $convoy->start_time = Carbon::parse($request->input('start_date').' '.$request->input('start_time'))->format('Y-m-d H:i');
            $convoy->setTypeByTime();
            return $convoy->save() ?
                redirect()->route('convoys.private', 'all')->with(['success' => 'Конвой успешно создан!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        $tmp = new Client();
        $servers = $tmp->servers()->get();
        $convoy = new Convoy();
        $convoy->start_time = Carbon::now();
        $convoy->trailer_public = true;
        $convoy->route = ['1' => null];
        return view('evoque.convoys.edit', [
            'booking' => false,
            'convoy' => $convoy,
            'servers' => $servers,
            'members' => Member::all(),
            'dlc' => $convoy->dlcList,
            'types' => Convoy::$timesToType
        ]);
    }

    public function edit(Request $request, $id, $booking = false){
        $this->authorize('update', Convoy::class);
        $convoy = Convoy::with(['audits' => function($query){
            $query->limit(10)->orderBy('created_at', 'desc');
        }])->where('id', $id)->firstOrFail();
        if($request->ajax() && $request->input('action') == 'remove_img'){
            $attr = $request->input('target');
            $convoy->$attr = null;
            return response()->json([
                'status' => $convoy->save() ? 'OK' : 'Failed'
            ]);
        }
        if($request->post()){
            $this->validate($request, $convoy->attributes_validation);
            $convoy->fill($request->post());
            $convoy->visible = $request->input('visible') === 'on' ? 1 : 0;
            $convoy->public = $request->input('public') === 'on' ? 1 : 0;
            $convoy->dlc = $request->input('dlc') ?? [];
            $convoy->truck_public = $request->input('truck_public') === 'on' ? 1 : 0;
            $convoy->trailer_public = $request->input('trailer_public') === 'on' ? 1 : 0;
            foreach($request->files as $key => $file){
                $convoy->deleteImages(public_path('/images/convoys/'), [$key]);
                if($key === 'route' && is_array($file)){
                    foreach($file as $i => $image){
                        $route_images[] = $convoy->saveImage($image, '/images/convoys/', $i);
                    }
                    $convoy->route = $route_images;
                }else{
                    $convoy->$key = $convoy->saveImage($file);
                }
            }
            $convoy->booking = false;
            $convoy->start_time = Carbon::parse($request->input('start_date').' '.$request->input('start_time'))->format('Y-m-d H:i');
            $convoy->setTypeByTime();
            return $convoy->save() ?
                redirect()->route('convoys.private', 'all')->with(['success' => 'Конвой успешно отредактирован!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        $tmp = new Client();
        $servers = $tmp->servers()->get();
        return view('evoque.convoys.edit', [
            'booking' => false,
            'convoy' => $convoy,
            'servers' => $servers,
            'members' => Member::all(),
            'dlc' => $convoy->dlcList,
            'types' => Convoy::$timesToType
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

    public function toggle(Request $request, $id){
        $this->authorize('update', Convoy::class);
        $convoy = Convoy::findOrFail($id);
        $convoy->visible = !$convoy->visible;
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
            ->addField('Выезд с места старта', $convoy->start_time->format('H:i') . ' по МСК', true)
            ->addField($convoy->dlc ?
                ':warning: Для участия требуется '. implode(', ', $convoy->dlc) :
                '| ',
                "> *Выставив маршрут, ваша поездка будет комфортной и спокойной!*")
            ->addField('Связь ведём через', $convoy->getCommunicationLink(), true)
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
