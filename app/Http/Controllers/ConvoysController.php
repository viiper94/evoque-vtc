<?php

namespace App\Http\Controllers;

use App\Convoy;
use App\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use TruckersMP\APIClient\Client;

class ConvoysController extends Controller{

    public function index(){
        $this->authorize('update', Convoy::class);
        $list = array();
        $convoys = Convoy::with('bookedBy')->orderBy('start_time', 'desc')->get();
        foreach($convoys as $convoy){
            $list[$convoy->start_time->format('d.m').', '.$convoy->start_time->isoFormat('dd')][] = $convoy;
        }
        return view('evoque.convoys.index', [
            'convoys' => $list
        ]);
    }

    public function private(Request $request, $all = false){
        if(!Auth::user()->member) abort(404);
        $convoys = Convoy::with('leadMember', 'leadMember.user');
        if(Auth::user()->cant('viewAny', Convoy::class) || !$all){
            $operator = '<';
            if(Carbon::now()->format('H') >= '21') $operator = '<=';
            $convoys = $convoys->whereDate('start_time', $operator, Carbon::tomorrow());
        }
        $convoys = $convoys->where('visible', '1')->orderBy('start_time')->get();
        $grouped = array();
        foreach($convoys as $convoy){
            $grouped[$convoy->start_time->isoFormat('DD.MM, dddd')][] = $convoy;
        }
        return view('evoque.convoys.private', [
            'grouped' => collect(array_reverse($grouped))->slice(0, !$all ? 7 : 20)
        ]);
    }

    public function public(){
        return view('convoys', [
            'convoy' => Convoy::where([
                ['visible', '=', '1'],
                ['public', '=', '1'],
                ['start_time', '>', Carbon::now()->subMinutes(45)->format('Y-m-d H:i')]
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
            $convoy->visible = $request->input('visible') === 'on';
            $convoy->public = $request->input('public') === 'on';
            $convoy->truck_public = $request->input('truck_public') === 'on';
            $convoy->trailer_public = $request->input('trailer_public') === 'on';
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
            $convoy->start_time = Carbon::parse($request->input('start_time'))->format('Y-m-d H:i');
            $convoy->setTypeByTime();
            return $convoy->save() ?
                redirect()->route('evoque.admin.convoys')->with(['success' => 'Конвой успешно создан!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        $tmp = new Client();
        $servers = $tmp->servers()->get();
        $convoy = new Convoy();
        $convoy->start_time = Carbon::now();
        $convoy->trailer_public = true;
        $convoy->route = ['1' => null];
        return view('evoque.convoys.edit', [
            'allowTimes' => null,
            'booking' => false,
            'convoy' => $convoy,
            'servers' => $servers,
            'members' => Member::all(),
            'dlc' => $convoy->dlcList
        ]);
    }

    public function edit(Request $request, $id, $booking = false){
        $this->authorize('update', Convoy::class);
        $convoy = Convoy::findOrFail($id);
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
            $convoy->visible = $request->input('visible') === 'on';
            $convoy->public = $request->input('public') === 'on';
            $convoy->dlc = $request->input('dlc') ?? [];
            $convoy->truck_public = $request->input('truck_public') === 'on';
            $convoy->trailer_public = $request->input('trailer_public') === 'on';
            foreach($request->files as $key => $file){
                $convoy->deleteImages(public_path('/images/convoys/'), [$key]);
                if($key === 'route' && is_array($file)){
                    foreach($file as $image){
                        $route_images[] = $convoy->saveImage($image);
                    }
                    $convoy->route = $route_images;
                }else{
                    $convoy->$key = $convoy->saveImage($file);
                }
            }
            $convoy->booking = false;
            $convoy->start_time = Carbon::parse($request->input('start_time'))->format('Y-m-d H:i');
            $convoy->setTypeByTime();
            return $convoy->save() ?
                redirect()->route('evoque.admin.convoys')->with(['success' => 'Конвой успешно отредактирован!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        $tmp = new Client();
        $servers = $tmp->servers()->get();
        return view('evoque.convoys.edit', [
            'allowTimes' => null,
            'booking' => false,
            'convoy' => $convoy,
            'servers' => $servers,
            'members' => Member::all(),
            'dlc' => $convoy->dlcList
        ]);
    }

    public function delete(Request $request, $id){
        $this->authorize('delete', Convoy::class);
        $convoy = Convoy::findOrFail($id);
        $convoy->deleteImages(public_path('/images/convoys/'));
        return $convoy->delete() ?
            redirect()->route('evoque.admin.convoys')->with(['success' => 'Конвой успешно удалён!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function toggle(Request $request, $id){
        $this->authorize('update', Convoy::class);
        $convoy = Convoy::findOrFail($id);
        $convoy->visible = !$convoy->visible;
        return $convoy->save() ?
            redirect()->route('evoque.admin.convoys')->with(['success' => 'Конвой успешно отредактирован!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function toDiscord($convoy_id){
        $this->authorize('update', Convoy::class);

        $convoy = Convoy::find($convoy_id);

        $fields = [
            [
                'name' => 'Место старта',
                'value' => $convoy->start_city.' - '. $convoy->start_company,
                'inline' => true
            ],
            [
                'name' => 'Место отдыха',
                'value' => $convoy->rest_city.' - '. $convoy->rest_company,
                'inline' => true
            ],
            [
                'name' => 'Место финиша',
                'value' => $convoy->finish_city.' - '. $convoy->finish_company,
                'inline' => true
            ],
            [
                'name' => 'Встречаемся на сервере',
                'value' => $convoy->server
            ],
            [
                'name' => 'Начало конвоя',
                'value' => $convoy->start_time->subMinutes(30)->format('H:i') . ' по МСК',
                'inline' => true
            ],
            [
                'name' => 'Выезд с места старта',
                'value' => $convoy->start_time->format('H:i') . ' по МСК',
                'inline' => true
            ]
        ];
        if($convoy->dlc){
            array_push($fields, [
                'name' => ':warning: Для участия требуется '. implode(', ', $convoy->dlc),
                'value' => "> *Выставив маршрут, ваша поездка будет комфортной и спокойной!*",
            ]);
        }
        array_push($fields, [
            'name' => 'Связь ведём через '. $convoy->communication,
            'value' => $convoy->getCommunicationLink(),
            'inline' => true
        ]);
        array_push($fields, [
            'name' => 'Канал на сервере',
            'value' => 'Открытый конвой',
            'inline' => true
        ]);
        array_push($fields, [
            'name' => '> *В колонне держим дистанцию не менее 70 метров по TAB и соблюдаем правила TruckersMP. Помимо рации, флуд, мат, а так же фоновая музыка запрещены и в голосовом канале Discord.*',
            'value' => '___',
        ]);
        if(isset($convoy->truck) && $convoy->truck_public){
            array_push($fields, [
                'name' => 'Тягач',
                'value' => $convoy->truck,
            ]);
        }
        if(isset($convoy->trailer) && $convoy->trailer_public){
            array_push($fields, [
                'name' => 'Прицеп',
                'value' => $convoy->trailer,
                'inline' => true
            ]);
            if(isset($convoy->cargo)){
                array_push($fields, [
                    'name' => 'Груз',
                    'value' => $convoy->cargo,
                    'inline' => true
                ]);
            }
            array_push($fields, [
                'name' => '> *Один груз — одна большая команда!*',
                'value' => '___',
            ]);
        }
        array_push($fields, [
            'name' => 'Маршрут',
            'value' => Url::to('/').'/images/convoys/'.$convoy->route[0]
        ]);

        $curl = curl_init('https://discord.com/api/webhooks/727068749601046530/2CJ_k6ML8Iflt2i_YRv_RbYNFKdgwcY1IjRZcvc-kC5KWJiQiVjkwm3iHtdFD7AYC5Bb');
        curl_setopt_array($curl, [
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                'embeds' => [
                    [
                        'title' => $convoy->title,
                        'type' => 'rich',
                        'url' => route('convoy.public'),
                        'color' => 14992641,
                        'footer' => [
                            'text' => 'ВТК EVOQUE'
                        ],
                        'image' => [
                            'url' => Url::to('/').'/images/convoys/'.$convoy->route[0]
                        ],
                        'fields' => $fields
                    ]
                ]
            ])
        ]);
        return curl_exec($curl) ? redirect()->back()->with(['success' => 'Конвой успешно запощен в Дискорде!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

}
