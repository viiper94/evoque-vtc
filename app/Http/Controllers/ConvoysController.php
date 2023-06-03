<?php

namespace App\Http\Controllers;

use App\Convoy;
use App\DLC;
use App\Member;
use App\Tuning;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Psy\Util\Str;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;
use Woeler\DiscordPhp\Exception\DiscordInvalidResponseException;
use Woeler\DiscordPhp\Message\DiscordEmbedMessage;
use Woeler\DiscordPhp\Webhook\DiscordWebhook;

class ConvoysController extends Controller{

    public function view(Request $request, $all = false){
        if(!Auth::user()?->member) abort(404);
        $convoys = Convoy::with('DLC', 'leadMember', 'leadMember.user', 'officialTruckTuning', 'officialTrailerTuning');
        if(Auth::user()->cant('viewAny', Convoy::class) || !$all){
            $operator = '<';
            if(now()->format('H') >= '21') $operator = '<=';
            $convoys = $convoys->whereDate('start_date', $operator, Carbon::tomorrow())
                ->where('visible', '1')
                ->orWhere(function($query){
                    $query->where('start_time', '>', Carbon::now())
                        ->where('booked_by_id', Auth::user()->member->id);
                });
        }
        $convoys = $convoys->orderBy('start_time', 'desc')->paginate(16);
        return view('evoque.convoys.index', [
            'all' => $all,
            'convoys' => $convoys->groupBy('start_date'),
            'paginator' => $convoys
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
                $old_images = $convoy->route;
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
                $convoy->syncImages($old_images, $convoy->route);
                if($request->hasFile('truck_image')) $convoy->truck_image = $convoy->saveImage($request->file('truck_image'), suffix: 'truck');
                if($request->hasFile('trailer_image')) $convoy->trailer_image = $convoy->saveImage($request->file('trailer_image'), suffix: 'trailer');
                if($request->hasFile('alt_trailer_image')) $convoy->alt_trailer_image = $convoy->saveImage($request->file('alt_trailer_image'), suffix: 'truck');
                $convoy->DLC()->sync($request->input('dlc'));
                $convoy->save();
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
                $file_name = $convoy->$attr;
                $convoy->$attr = null;
                $convoy->save();
                if(is_file(public_path('images/convoys/').$file_name)){
                    unlink(public_path('images/convoys/').$file_name);
                }
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

    public function compress(){

        // getting last modified convoy id
        $completed = json_decode(file_get_contents(resource_path('compressed.json')), true);
        $last = array_reverse(array_keys($completed))[0] ?? 0;

        // selecting next 10 convoys
        $convoys = Convoy::select('*')
            ->whereDate('created_at', '<', Carbon::now()->subMonth(6)->format('Y-m-d'))
            ->where('id', '>', $last)
            ->orderBy('id')
            ->take(50)
            ->get();

//        dd($convoys);

        foreach ($convoys as $convoy) {

            // put message in file
            $completed[$convoy->id] = 'Processing...';


            $new_alt_trailer_name = null;
            $new_trailer_name = null;
            $new_truck_name = null;


            // compressing route images
            $route_images = $convoy->route;

            foreach ($convoy->route as $key => $image_name){

                if (is_file(public_path('images/convoys/').$image_name)){
                    $img = Image::load(public_path('images/convoys/').$image_name);

                    // assigning new name
                    $new_name = $convoy->start_time->format('Y-m-d')
                        . '_'
                        . $convoy->id
                        . '_'
                        . \Illuminate\Support\Str::random(5)
                        . '.jpg';

                    $img->format(Manipulations::FORMAT_JPG)
                        ->quality(70)
                        ->width(1280)
                        ->save(public_path('images/convoys/').$new_name);

                    $route_images[$key] = $new_name;
                }


            }


            // compressing truck image
            if ($convoy->truck_image && is_file(public_path('images/convoys/').$convoy->truck_image)){

                $img = Image::load(public_path('images/convoys/').$convoy->truck_image);

                // assigning new truck name
                $new_truck_name = $convoy->start_time->format('Y-m-d')
                    . '_'
                    . $convoy->id
                    . '_truck_'
                    . \Illuminate\Support\Str::random(5)
                    . '.jpg';

                $img->format(Manipulations::FORMAT_JPG)
                    ->quality(70)
                    ->width(1280)
                    ->save(public_path('images/convoys/').$new_truck_name);

            }



            // compressing trailer image
            if ($convoy->trailer_image && is_file(public_path('images/convoys/').$convoy->trailer_image)){

                $img = Image::load(public_path('images/convoys/').$convoy->trailer_image);

                // assigning new trailer name
                $new_trailer_name = $convoy->start_time->format('Y-m-d')
                    . '_'
                    . $convoy->id
                    . '_trailer_'
                    . \Illuminate\Support\Str::random(5)
                    . '.jpg';

                $img->format(Manipulations::FORMAT_JPG)
                    ->quality(70)
                    ->width(1280)
                    ->save(public_path('images/convoys/').$new_trailer_name);

            }



            // compressing alt trailer image
            if ($convoy->alt_trailer_image && is_file(public_path('images/convoys/').$convoy->alt_trailer_image)){

                $img = Image::load(public_path('images/convoys/').$convoy->alt_trailer_image);

                // assigning new trailer name
                $new_alt_trailer_name = $convoy->start_time->format('Y-m-d')
                    . '_'
                    . $convoy->id
                    . '_alt_trailer_'
                    . \Illuminate\Support\Str::random(5)
                    . '.jpg';

                $img->format(Manipulations::FORMAT_JPG)
                    ->quality(70)
                    ->width(1280)
                    ->save(public_path('images/convoys/').$new_alt_trailer_name);

            }


            //deleting old images
            foreach ($convoy->route as $image){
                if (is_file(public_path('images/convoys/').$image))
                    unlink(public_path('images/convoys/').$image);
            }

            if (is_file(public_path('images/convoys/').$convoy->truck_image))
                unlink(public_path('images/convoys/').$convoy->truck_image);

            if (is_file(public_path('images/convoys/').$convoy->trailer_image))
                unlink(public_path('images/convoys/').$convoy->trailer_image);

            if (is_file(public_path('images/convoys/').$convoy->alt_trailer_image))
                unlink(public_path('images/convoys/').$convoy->alt_trailer_image);


            // saving convoy
            $convoy->route = $route_images;
            $convoy->truck_image = $new_truck_name ?? $convoy->truck_image;
            $convoy->trailer_image = $new_trailer_name ?? $convoy->trailer_image;
            $convoy->alt_trailer_image = $new_alt_trailer_name ?? $convoy->alt_trailer_image;
            $convoy->save();

            // put message in file
            $completed[$convoy->id] = 'Done!';

        }

        file_put_contents(resource_path('compressed.json'), json_encode($completed, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));

        dd($completed);
    }

}
