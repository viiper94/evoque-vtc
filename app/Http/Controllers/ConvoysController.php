<?php

namespace App\Http\Controllers;

use App\Convoy;
use App\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use TruckersMP\APIClient\Client;

class ConvoysController extends Controller{

    public function index(){
        return view('evoque.convoys.index', [
            'convoys' => Convoy::all()
        ]);
    }

    public function add(Request $request){
        if(Gate::denies('manage_convoys')) abort(403);
        if($request->post()){
            $this->validate($request, [
                'title' => 'required|string',
                'start_time' => 'required|date',
                'start' => 'required|string',
                'rest' => 'required|string',
                'finish' => 'required|string',
                'communication' => 'required|string',
                'communication_link' => 'required|string',
                'communication_channel' => 'required|string',
                'route' => 'required|url',
            ]);
            $convoy = new Convoy();
            $convoy->fill($request->post());
            $convoy->visible = $request->input('visible') === 'on';
            $convoy->public = $request->input('public') === 'on';
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
            'members' => Member::all()
        ]);
    }

    public function edit(Request $request, $id){
        if(Gate::denies('manage_convoys')) abort(403);
        if($request->post()){
            $this->validate($request, [
                'title' => 'required|string',
                'start_time' => 'required|date',
                'start' => 'required|string',
                'rest' => 'required|string',
                'finish' => 'required|string',
                'communication' => 'required|string',
                'route' => 'required|url',
            ]);
            $convoy = Convoy::findOrFail($id);
            $convoy->fill($request->post());
            $convoy->visible = $request->input('visible') === 'on';
            $convoy->public = $request->input('public') === 'on';
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
            'members' => Member::all()
        ]);
    }

    public function delete(Request $request, $id){
        if(Gate::denies('manage_convoys')) abort(403);
        $convoy = Convoy::findOrFail($id);
        return $convoy->delete() ?
            redirect()->route('evoque.convoys')->with(['success' => 'Конвой успешно удалён!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

}
