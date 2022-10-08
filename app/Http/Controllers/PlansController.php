<?php

namespace App\Http\Controllers;

use App\Convoy;
use App\DLC;
use App\Member;
use App\Tuning;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlansController extends Controller{

    private $allowedConvoysPerDay = [
        'понедельник' => [1],
        'вторник' => [0, 1],
        'среда' => [1],
        'четверг' => [0, 1],
        'пятница' => [0, 1, 2],
        'суббота' => [0, 1, 2],
        'воскресенье' => [0, 1],
    ];

    public function plans(){
        if(!Auth::user()?->member) abort(404);
        $days = [];
        $convoys = Convoy::whereDate('start_time', '>=', Carbon::today())->where('visible', '1')->get();

        for($i = 0; $i <= 7; $i++){
            $convoy_that_day = array();
            // adding existed convoys to day
            foreach($convoys as $convoy){
                if($convoy->start_time->format('d.m.Y') === Carbon::today()->addDays($i)->format('d.m.Y')){
                    $convoy_that_day[$convoy->type][] = $convoy;
                }
            }
            // adding empty slots to day
            foreach($this->allowedConvoysPerDay[Carbon::now()->addDays($i)->isoFormat('dddd')] as $type){
                if(key_exists($type, $convoy_that_day)){
                    continue;
                }else{
                    $convoy_that_day[$type][] = [];
                }
            }
            // sorting and merging
            ksort($convoy_that_day);
            $days[Carbon::now()->addDays($i)->format('d.m')] = [
                'date' => Carbon::now()->addDays($i),
                'convoys' => $convoy_that_day
            ];
        }
        return view('evoque.convoys.plans.index', [
            'days' => $days,
            'members' => Member::where('visible', '1')->orderBy('nickname')->get(),
            'types' => Convoy::$timesToType
        ]);
    }

    public function quickBook(Request $request){
        $this->authorize('quickBook', Convoy::class);
        $this->validate($request, [
            'title' => 'required|string',
            'date' => 'required|date_format:d.m.Y',
            'time' => 'required|date_format:H:i',
            'lead' => 'required|string',
        ]);

        $booking = new Convoy();
        $booking->title = trim($request->input('title'));
        $booking->lead = $request->input('lead');
        $booking->booking = true;
        $booking->visible = true;
        $booking->start_time = Carbon::parse($request->input('date').' '. $request->input('time'))->format('Y-m-d H:i');
        $booking->setTypeByTime();
        return $booking->save() ?
            redirect()->back()->with(['success' => 'Конвой забронирован!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function book(Request $request, $offset, $type){
        $this->authorize('book', Convoy::class);
        $convoy_that_day = Convoy::whereDate('start_time', '=', Carbon::today()->addDays($offset))->where(['type' => $type, 'visible' => '1'])->get();
        if(!in_array($type, [0, 1, 2, 3])) return redirect()->route('evoque.convoys.plans')->withErrors(['Что то пошло не так =(']);
        if(count($convoy_that_day) > 0) return redirect()->route('evoque.convoys.plans')->withErrors(['Уже забронирован конвой на этот период!']);
        $convoy = new Convoy([
            'title' => $offset == 0 ? 'Внеплановый конвой' : 'Закрытый конвой',
            'trailer_public' => false,
            'truck_public' => false,
            'public' => false,
            'communication' => 'Discord',
            'communication_link' => 'https://discord.gg/Gj53a8d',
            'communication_channel' => 'Комната для конвоев',
            'lead' => Auth::user()->member->nickname,
        ]);
        if($request->ajax() && $request->post()){
            $this->validate($request, $convoy->attributes_validation);
            $convoy->fill($request->post());
            if(!in_array($request->input('start_time'), Convoy::$timesToType[$type])){
                return response()->json([
                    'errors' => ['start_time' => 'Выбранное время не соответствует типу конвоя'],
                    'message' => 'Выбранное время не соответствует типу конвоя'
                ], 422);
            }
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
            $convoy->visible = $offset == 0;
            $convoy->start_time = Carbon::parse(Carbon::today()->addDays($offset)->format('Y-m-d').' '.$request->input('start_time'))->format('Y-m-d H:i');
            $convoy->setTypeByTime();
            $convoy->booking = true;
            $convoy->booked_by_id = Auth::user()->member->id;
            $convoy->lead = Auth::user()->member->nickname;
            if($convoy->save()){
                $convoy->DLC()->sync($request->input('dlc'));
                return response()->json([
                    'redirect' => route('evoque.convoys.plans'),
                    'message' => $offset == 0 ?
                        'Внеплановый конвой создан, не забудь продублировать регламент в чате в ВК!' :
                        'Регламент отправлен на модерацию и появится в планах после одобрения логистом!'
                ]);
            }else{
                return response()->json(['neok' => ['message' => 'Возникла ошибка']]);
            }
        }
        $convoy->start_time = Carbon::today()->addDays($offset)->addHours($type == 0 ? 16 : ($type == 2 ? 22 : 19))->addMinutes($type == 1 ? 30 : 0);
        $servers = $convoy->defaultServers;
        return view('evoque.convoys.edit', [
            'booking' => true,
            'convoy' => $convoy,
            'servers' => $servers,
            'members' => Member::where('id', Auth::user()->member->id)->get(),
            'dlc' => DLC::orderBy('sort')->get()->groupBy('game'),
            'types' => [$type => Convoy::$timesToType[$type]],
            'trucks_tuning' => Tuning::where(['type' => 'truck', 'visible' => '1'])->get()->groupBy('vendor'),
            'trailers_tuning' => Tuning::where(['type' => 'trailer', 'visible' => '1'])->get()->groupBy('vendor'),
        ]);
    }

}
