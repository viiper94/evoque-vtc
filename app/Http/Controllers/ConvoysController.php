<?php

namespace App\Http\Controllers;

use App\Convoy;
use App\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use TruckersMP\APIClient\Client;

class ConvoysController extends Controller{

    private $attributes_validation = [
        'title' => 'required|string',
        'start_time' => 'required|date',
        'server' => 'required|string',

        'route' => 'nullable|image',
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
            'Going East!',
            'Scandinavia',
            'Vive la France!',
            'Italia',
            'Beyond The Baltic Sea',
            'Road To The Black Sea',
            'Iberia',
        ],
        'ats' => [
            'New Mexico',
            'Oregon',
            'Washington',
            'Utah',
            'Idaho',
            'Colorado',
        ]
    ];

    public function index(){
        if(Gate::denies('manage_convoys')) abort(403);
        return view('evoque.convoys.index', [
            'convoys' => Convoy::orderBy('start_time', 'desc')->get()
        ]);
    }

    public function convoys(Request $request){
        if(Auth::check()){
            $operator = '<';
            if(Carbon::now()->format('H') >= '21') $operator = '<=';
            $convoys = Convoy::where('visible', '1')->whereDate('start_time', $operator, Carbon::tomorrow())->orderBy('start_time')->get();
            $grouped = array();
            foreach($convoys as $convoy){
                $grouped[$convoy->start_time->isoFormat('DD.MM, dddd')][] = $convoy;
                if(count($grouped) >= 5) break;
            }
            return view('evoque.convoys.private', [
                'grouped' => array_reverse($grouped)
            ]);
        }else{
            return view('convoys', [
                'convoy' => Convoy::where(['visible' => '1', 'public' => '1'])->first()
            ]);
        }
    }

    public function add(Request $request){
        if(Gate::denies('manage_convoys')) abort(403);
        if($request->post()){
            $this->validate($request, $this->attributes_validation);
            // TODO filter photo links
            // TODO Multiple route images
            $convoy = new Convoy();
            $convoy->fill($request->post());
            $convoy->visible = $request->input('visible') === 'on';
            $convoy->public = $request->input('public') === 'on';
            foreach($request->files as $key => $file){
                $name = md5(time().$file->getClientOriginalName()).'.'. $file->getClientOriginalExtension();
                $file->move(public_path('/images/convoys/'), $name);
                $convoy->$key = '/images/convoys/'.$name;
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
        return view('evoque.convoys.edit', [
            'convoy' => $convoy,
            'servers' => $servers,
            'members' => Member::all(),
            'dlc' => $this->dlcList
        ]);
    }

    public function edit(Request $request, $id){
        if(Gate::denies('manage_convoys')) abort(403);
        if($request->post()){
            $this->validate($request, $this->attributes_validation);
            // TODO filter photo links
            // TODO Multiple route images
            $convoy = Convoy::findOrFail($id);
            $convoy->fill($request->post());
            $convoy->visible = $request->input('visible') === 'on';
            $convoy->public = $request->input('public') === 'on';
            foreach($request->files as $key => $file){
                $name = md5(time().$file->getClientOriginalName()).'.'. $file->getClientOriginalExtension();
                $file->move(public_path('/images/convoys/'), $name);
                $convoy->$key = '/images/convoys/'.$name;
            }
            $convoy->start_time = Carbon::parse($request->input('start_time'))->format('Y-m-d H:i');
            return $convoy->save() ?
                redirect()->route('evoque.convoys')->with(['success' => 'Конвой успешно отредактирован!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        $tmp = new Client();
        $servers = $tmp->servers()->get();
        return view('evoque.convoys.edit', [
            'convoy' => Convoy::findOrFail($id),
            'servers' => $servers,
            'members' => Member::all(),
            'dlc' => $this->dlcList
        ]);
    }

    public function delete(Request $request, $id){
        if(Gate::denies('manage_convoys')) abort(403);
        $convoy = Convoy::findOrFail($id);
        // TODO deleting convoy images
        return $convoy->delete() ?
            redirect()->route('evoque.convoys')->with(['success' => 'Конвой успешно удалён!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    // TODO Convoys screen TAB system
    // TODO Convoy plans page
}
