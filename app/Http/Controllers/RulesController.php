<?php

namespace App\Http\Controllers;

use App\Rules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RulesController extends Controller{

    public function index($type = 'public'){
        return view('rules', [
            'rules' => Rules::where('public', $type === 'public' ? '1' : '0')->orderBy('paragraph')->get(),
            'public' => $type === 'public'
        ]);
    }

    public function edit(Request $request, $id){
        if(Gate::denies('admin')) abort(403);
        if($request->post()){
            $this->validate($request, [
                'paragraph' => 'required|numeric',
                'title' => 'required|string',
                'text' => 'required',
            ]);
            $p = Rules::findOrFail($id);
            $p->fill($request->post());
            $p->public = $request->input('public') == 'true';
            return $p->save() ?
                redirect()->route('rules', $p->public ? 'public' : 'private')->with(['success' => 'Параграф правил успешно изменён!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('evoque.rules.edit', [
            'rules' => Rules::findOrFail($id)
        ]);
    }

    public function add(Request $request){
        if(Gate::denies('admin')) abort(403);
        if($request->post()){
            $this->validate($request, [
                'paragraph' => 'required|numeric',
                'title' => 'required|string',
                'text' => 'required',
            ]);
            $p = new Rules();
            $p->fill($request->post());
            $p->public = $request->input('public') == 'true';
            return $p->save() ?
                redirect()->route('rules', $p->public ? 'public' : 'private')->with(['success' => 'Параграф правил успешно создан!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('evoque.rules.edit', [
            'rules' => new Rules()
        ]);
    }

    public function delete(Request $request, $id){
        if(Gate::denies('admin')) abort(403);
        $p = Rules::findOrFail($id);
        $redirect = $p->public ? 'public' : 'private';
        return $p->delete() ?
            redirect()->route('rules', $redirect)->with(['success' => 'Параграф правил успешно удалён!']) :
            redirect()->back()->withErrors([',Возникла ошибка =(']);
    }

}
