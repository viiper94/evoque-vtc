<?php

namespace App\Http\Controllers;

use App\Convoy;
use App\Member;
use App\TrucksTuning;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use TruckersMP\APIClient\Client;

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
            foreach($convoys as $convoy){
                if($convoy->start_time->format('d.m.Y') === Carbon::today()->addDays($i)->format('d.m.Y')){
                    $convoy_that_day[$convoy->type][] = $convoy;
                }
            }
            foreach($this->allowedConvoysPerDay[Carbon::now()->addDays($i)->isoFormat('dddd')] as $type){
                if(key_exists($type, $convoy_that_day)){
                    continue;
                }elseif($i !== 0){
                    $convoy_that_day[$type][] = [];
                }
            }
            ksort($convoy_that_day);
            $days[Carbon::now()->addDays($i)->format('d.m')] = [
                'date' => Carbon::now()->addDays($i),
                'convoys' => $convoy_that_day
            ];
        }
//        dd($days);
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
        $convoy_that_day = Convoy::whereDate('start_time', '=', Carbon::today()->addDays($offset))->where('type', $type)->get();
        if($offset === '0') return redirect()->route('evoque.convoys.plans')->withErrors(['На сегодня уже нельзя бронировать конвои!']);
        if(!in_array($type, [0, 1, 2, 3])) return redirect()->route('evoque.convoys.plans')->withErrors(['Что то пошло не так =(']);
        if(count($convoy_that_day) > 0) return redirect()->route('evoque.convoys.plans')->withErrors(['Уже забронирован конвой на этот период!']);
        $convoy = new Convoy([
            'title' => 'Закрытый конвой',
            'trailer_public' => false,
            'truck_public' => false,
            'public' => false,
            'visible' => false,
            'route' => ['1' => null],
            'communication' => 'Discord',
            'communication_link' => 'https://discord.gg/Gj53a8d',
            'communication_channel' => 'Комната для конвоев',
            'lead' => Auth::user()->member->nickname,
        ]);
        if($request->post()){
            $this->validate($request, $convoy->attributes_validation);
            $convoy->fill($request->post());
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
            if($request->input('truck_with_tuning')){
                $tuning = TrucksTuning::find($request->input('truck_with_tuning'));
                $convoy->truck_image = '/images/tuning/'.$tuning->image;
            }
            $convoy->start_time = Carbon::parse($request->input('start_date').' '.$request->input('start_time'))->format('Y-m-d H:i');
            $convoy->setTypeByTime();
            $convoy->booking = true;
            $convoy->booked_by_id = Auth::user()->member->id;
            return $convoy->save() ?
                redirect()->route('evoque.convoys.plans')->with(['success' => 'Регламент отправлен на модерацию и появится в планах после одобрения логистом!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        $convoy->start_time = Carbon::today()->addDays($offset)->addHours($type == 0 ? 16 : ($type == 2 ? 22 : 19))->addMinutes($type == 1 ? 30 : 0);
//        $tmp = new Client();
//        $servers = $tmp->servers()->get();
        $servers = $convoy->defaultServers;
        return view('evoque.convoys.edit', [
            'booking' => true,
            'convoy' => $convoy,
            'servers' => $servers,
            'members' => Member::where('id', Auth::user()->member->id)->get(),
            'dlc' => $convoy->dlcList,
            'types' => [$type => Convoy::$timesToType[$type]],
            'trucks_tuning' => TrucksTuning::whereVisible(true)->get()
        ]);
    }

}
