<?php

namespace App\Http\Controllers;

use App\Convoy;
use App\Member;
use App\Role;
use App\Tab;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use TruckersMP\APIClient\Client;

class ConvoysController extends Controller{

    private $attributes_validation = [
        'title' => 'required|string',
        'start_time' => 'required|date',
        'server' => 'required|string',

        'route' => 'nullable|array',
        'start_city' => 'required|string',
        'start_company' => 'nullable|string',
        'rest_city' => 'required|string',
        'rest_company' => 'nullable|string',
        'finish_city' => 'required|string',
        'finish_company' => 'nullable|string',
        'dlc' => 'nullable|array',

        'communication' => 'required|string',
        'communication_link' => 'required|string',
        'communication_channel' => 'required|string',
        'lead' => 'nullable|string',

        'truck_image' => 'nullable|image',
        'truck' => 'nullable|string',
        'truck_tuning' => 'nullable|string',
        'truck_paint' => 'nullable|string',

        'trailer_image' => 'nullable|image',
        'trailer' => 'nullable|string',
        'trailer_tuning' => 'nullable|string',
        'trailer_paint' => 'nullable|string',
        'cargo' => 'nullable|string',

        'alt_trailer_image' => 'nullable|image',
        'alt_trailer' => 'nullable|string',
        'alt_trailer_tuning' => 'nullable|string',
        'alt_trailer_paint' => 'nullable|string',
        'alt_cargo' => 'nullable|string',
    ];

    private $dlcList = [
        'ets2' => [
            'ProMods',
            'DLC Going East!',
            'DLC Scandinavia',
            'DLC Vive la France!',
            'DLC Italia',
            'DLC Beyond The Baltic Sea',
            'DLC Road To The Black Sea',
            'DLC Iberia',
            'DLC High Power Cargo Pack',
            'DLC Heavy Cargo Pack'
        ],
        'ats' => [
            'DLC New Mexico',
            'DLC Oregon',
            'DLC Washington',
            'DLC Utah',
            'DLC Idaho',
            'DLC Colorado',
            'DLC Heavy Cargo Pack'
        ]
    ];

    private $allowedConvoysPerDay = [
        'понедельник' => 1,
        'вторник' => 2,
        'среда' => 1,
        'четверг' => 2,
        'пятница' => 3,
        'суббота' => 3,
        'воскресенье' => 2,
    ];

    public function index(){
        if(Gate::denies('manage_convoys')) abort(403);
        return view('evoque.convoys.index', [
            'convoys' => Convoy::with('bookedBy')->orderBy('start_time', 'desc')->paginate(10)
        ]);
    }

    public function convoys(Request $request, $public = false){
        if(Auth::check() && !$public){
            $operator = '<';
            if(Carbon::now()->format('H') >= '21') $operator = '<=';
            $convoys = Convoy::with('leadMember', 'leadMember.user')->where('visible', '1')->whereDate('start_time', $operator, Carbon::tomorrow())->orderBy('start_time')->get();
            $grouped = array();
            foreach($convoys as $convoy){
                $grouped[$convoy->start_time->isoFormat('DD.MM, dddd')][] = $convoy;
            }
            return view('evoque.convoys.private', [
                'grouped' => collect(array_reverse($grouped))->slice(0, 7)
            ]);
        }else if(Gate::allows('manage_convoys') && $public === 'all'){
            $convoys = Convoy::orderBy('start_time')->get();
            $grouped = array();
            foreach($convoys as $convoy){
                $grouped[$convoy->start_time->isoFormat('DD.MM, dddd')][] = $convoy;
            }
            return view('evoque.convoys.private', [
                'grouped' => array_reverse($grouped)
            ]);
        }else{
            return view('convoys', [
                'convoy' => Convoy::where([
                    ['visible', '=', '1'],
                    ['public', '=', '1'],
                    ['start_time', '>', Carbon::now()->subMinutes(45)->format('Y-m-d H:i')]
                ])->orderBy('start_time')->first()
            ]);
        }
    }

    public function add(Request $request){
        if(Gate::denies('manage_convoys')) abort(403);
        if($request->ajax() && $request->input('action') == 'remove_img'){
            return response()->json([
                'status' => 'OK'
            ]);
        }
        if($request->post()){
            $this->validate($request, $this->attributes_validation);
            $convoy = new Convoy();
            $convoy->fill($request->post());
            $convoy->visible = $request->input('visible') === 'on';
            $convoy->public = $request->input('public') === 'on';
            $convoy->truck_public = $request->input('truck_public') === 'on';
            $convoy->trailer_public = $request->input('trailer_public') === 'on';
            foreach($request->files as $key => $file){
                if($key === 'route' && is_array($file)){
                    $route_images = array();
                    foreach($file as $i => $route){
                        $route_images[] = $this->saveImage($route);
                    }
                    $convoy->route = $route_images;
                }else{
                    $convoy->$key = $this->saveImage($file);
                }
            }
            $convoy->start_time = Carbon::parse($request->input('start_time'))->format('Y-m-d H:i');
            return $convoy->save() ?
                redirect()->route('evoque.convoys')->with(['success' => 'Конвой успешно создан!']) :
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
            'dlc' => $this->dlcList
        ]);
    }

    public function edit(Request $request, $id, $booking = false){
        if(Gate::denies('manage_convoys')) abort(403);
        $convoy = Convoy::findOrFail($id);
        if($request->ajax() && $request->input('action') == 'remove_img'){
            $attr = $request->input('target');
            $convoy->$attr = null;
            return response()->json([
                'status' => $convoy->save() ? 'OK' : 'Failed'
            ]);
        }
        if($request->post()){
            $this->validate($request, $this->attributes_validation);
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
                        $route_images[] = $this->saveImage($image);
                    }
                    $convoy->route = $route_images;
                }else{
                    $convoy->$key = $this->saveImage($file);
                }
            }

            $convoy->start_time = Carbon::parse($request->input('start_time'))->format('Y-m-d H:i');
            return $convoy->save() ?
                redirect()->route('evoque.convoys')->with(['success' => 'Конвой успешно отредактирован!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        $tmp = new Client();
        $servers = $tmp->servers()->get();
        return view('evoque.convoys.edit', [
            'booking' => false,
            'convoy' => $convoy,
            'servers' => $servers,
            'members' => Member::all(),
            'dlc' => $this->dlcList
        ]);
    }

    public function delete(Request $request, $id){
        if(Gate::denies('manage_convoys')) abort(403);
        $convoy = Convoy::findOrFail($id);
        $convoy->deleteImages(public_path('/images/convoys/'));
        return $convoy->delete() ?
            redirect()->route('evoque.convoys')->with(['success' => 'Конвой успешно удалён!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function toggle(Request $request, $id){
        if(Gate::denies('manage_convoys')) abort(403);
        $convoy = Convoy::findOrFail($id);
        $convoy->visible = !$convoy->visible;
        return $convoy->save() ?
            redirect()->route('evoque.convoys')->with(['success' => 'Конвой успешно отредактирован!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    private function saveImage(UploadedFile $file, $path = '/images/convoys/'){
        $name = md5(time().$file->getClientOriginalName()).'.'. $file->getClientOriginalExtension();
        $file->move(public_path($path), $name);
        return $name;
    }

    public function tab(){
        return view('evoque.convoys.tab.index', [
            'tabs' => Tab::with(['member', 'lead'])->orderBy('date', 'desc')->get()
        ]);
    }

    public function addTab(Request $request){
        if(Gate::denies('lead_convoys')) abort(403);
        if($request->post()){
            $this->validate($request, [
                'convoy_title' => 'required|string',
                'lead_id' => 'required|numeric',
                'date' => 'required|date_format:d.m.Y',
                'screenshot' => 'required|image',
                'description' => 'nullable|string',
            ]);
            $tab = new Tab();
            $tab->fill($request->post());
            $tab->description = htmlentities(trim($request->input('description')));
            $tab->member_id = Auth::user()->member->id;
            $tab->date = Carbon::parse($request->input('date'))->format('Y-m-d');
            if($request->file('screenshot')){
                $tab->screenshot = $this->saveImage($request->file('screenshot'), '/images/convoys/tab/');
            }
            return $tab->save() ?
                redirect()->route('evoque.convoys.tab')->with(['success' => 'Скрин TAB успешно подан!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('evoque.convoys.tab.edit', [
            'tab' => new Tab(),
            'members' => Member::where('visible', '1')->get()
        ]);
    }

    public function editTab(Request $request, $id){
        if(Gate::denies('lead_convoys')) abort(403);
        $tab = Tab::with(['member', 'lead'])->where('id', $id)->first();
        if($request->post()){
            $this->validate($request, [
                'convoy_title' => 'required|string',
                'lead_id' => 'required|numeric',
                'date' => 'required|date_format:d.m.Y',
                'screenshot' => 'nullable|image',
            ]);
            $tab->fill($request->post());
            $tab->description = htmlentities(trim($request->input('description')));
            $tab->date = Carbon::parse($request->input('date'))->format('Y-m-d');
            if($request->file('screenshot')){
                if(is_file(public_path('/images/convoys/tab/').$tab->screenshot)){
                    unlink(public_path('/images/convoys/tab/').$tab->screenshot);
                }
                $tab->screenshot = $this->saveImage($request->file('screenshot'), '/images/convoys/tab/');
            }
            return $tab->save() ?
                redirect()->route('evoque.convoys.tab')->with(['success' => 'Скрин TAB успешно отредактирован!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('evoque.convoys.tab.edit', [
            'tab' => $tab,
            'members' => Member::where('visible', '1')->get()
        ]);
    }

    public function deleteTab(Request $request, $id){
        if(Gate::denies('lead_convoys')) abort(403);
        $tab = Tab::findOrFail($id);
        if(is_file(public_path('/images/convoys/tab/').$tab->screenshot)){
            unlink(public_path('/images/convoys/tab/').$tab->screenshot);
        }
        return $tab->delete() ?
            redirect()->route('evoque.convoys.tab')->with(['success' => 'Скрин TAB успешно удалён!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function acceptTab(Request $request, $id){
        if(Gate::denies('manage_table')) abort(403);
        $tab = Tab::with(['member', 'lead'])->where(['id' => $id, 'status' => 0])->firstOrFail();
        if($request->post()){
            $this->validate($request, [
                'scores' => 'nullable|array',
                'lead' => 'nullable|string'
            ]);
            $lead = explode(',', $request->input('lead'));
            foreach($request->input('scores') as $member_id => $value){
                $member = Member::find($member_id);
                $member->convoys += 1;
                if(isset($member->scores)) $member->scores += $value;
                if(isset($member->money) && $lead[0] == $member->id) $member->money += $lead[1];
                $member->save();
            }
            $tab->status = 1;
            return $tab->save() ?
                redirect()->route('evoque.convoys.tab')->with(['success' => 'Скрин TAB успешно отредактирован!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('evoque.convoys.tab.accept', [
            'tab' => $tab,
            'members' => Member::where('visible', '1')->orderBy('nickname')->get(),
            'roles' => Role::with([
                'members' => function($query){
                    $query->where('visible', 1)->orderBy('sort', 'desc')->orderBy('scores', 'desc')->orderBy('join_date', 'asc');
                },
                'members.role' => function($query){
                    $query->where('visible', '1');
                }
            ])->where('visible', 1)->get()->groupBy('group')->filter(function($roles){
                foreach($roles as $role){
                    foreach($role->members as $member){
                        $has = $member->topRole() === $role->id;
                        if($has) return true;
                    }
                }
                return false;
            })
        ]);
    }

    public function plans(Request $request){
        if(Auth::guest()) return redirect()->route('auth.steam');
        if($request->post()){
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
            $booking->booked_by_id = Auth::user()->member->id;
            $booking->start_time = Carbon::parse($request->input('date').' '. $request->input('time'))->format('Y-m-d H:i');
            return $booking->save() ?
                redirect()->back()->with(['success' => 'Конвой забронирован!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        $days = [];
        $convoys = Convoy::whereDate('start_time', '>=', Carbon::today())->where('visible', '1')->get();
        for($i = 0; $i <= 7; $i++){
            $convoy_that_day = array();
            foreach($convoys as $convoy){
                if($convoy->start_time->format('d.m.Y') === Carbon::now()->addDays($i)->format('d.m.Y')){
                    $convoy_that_day[] = $convoy;
                }
            }
            $days[$i] = [
                'date' => Carbon::now()->addDays($i),
                'convoys' => $convoy_that_day,
                'allowedToBook' => $this->allowedConvoysPerDay[Carbon::now()->addDays($i)->isoFormat('dddd')] - count($convoy_that_day)
            ];
        }
        return view('evoque.convoys.plans.index', [
            'days' => $days,
            'members' => Member::where('visible', '1')->orderBy('nickname')->get(),
        ]);
    }

    public function book(Request $request, $offset){
        if(Gate::denies('lead_convoys')) abort(403);
        $convoy_that_day = Convoy::whereDate('start_time', '=', Carbon::today()->addDays($offset))->get();
        if($offset === '0' || $this->allowedConvoysPerDay[Carbon::now()->addDays($offset)->isoFormat('dddd')] - count($convoy_that_day) === 0){
            return redirect()->route('evoque.convoys.plans')->withErrors(['Нельзя больше бронировать на этот день!']);
        }
        $convoy = new Convoy([
            'trailer_public' => false,
            'truck_public' => false,
            'public' => false,
            'visible' => false,
            'route' => ['1' => null],
            'communication' => 'Discord',
            'communication_link' => 'https://discord.gg/Gj53a8d',
            'communication_channel' => 'Комната для конвоев',
            'lead' => Auth::user()->member->nickname
        ]);
        if($request->post()){
            $this->validate($request, $this->attributes_validation);
            $convoy->fill($request->post());
            foreach($request->files as $key => $file){
                if($key === 'route' && is_array($file)){
                    $route_images = array();
                    foreach($file as $i => $route){
                        $route_images[] = $this->saveImage($route);
                    }
                    $convoy->route = $route_images;
                }else{
                    $convoy->$key = $this->saveImage($file);
                }
            }
            $convoy->start_time = Carbon::parse($request->input('start_time'))->format('Y-m-d H:i');
            $convoy->booking = true;
            $convoy->booked_by_id = Auth::user()->member->id;
            return $convoy->save() ?
                redirect()->route('evoque.convoys.plans')->with(['success' => 'Регламент отправлен на модерацию и появится в планах после одобрения логистом!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        $convoy->start_time = Carbon::now()->addDays($offset);
        $tmp = new Client();
        $servers = $tmp->servers()->get();
        return view('evoque.convoys.edit', [
            'booking' => true,
            'convoy' => $convoy,
            'servers' => $servers,
            'members' => Member::where('id', Auth::user()->member->id)->get(),
            'dlc' => $this->dlcList
        ]);
    }

}
