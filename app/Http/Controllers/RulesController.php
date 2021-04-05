<?php

namespace App\Http\Controllers;

use App\Rules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class RulesController extends Controller{

    public function index($type = 'public'){
        if(Auth::guest() || !Auth::user()->member) $type = 'public';
        return view('rules', [
            'rules' => Rules::with('audits')->where('public', $type === 'public' ? '1' : '0')->orderBy('paragraph')->get(),
            'public' => $type === 'public'
        ]);
    }

    public function edit(Request $request, $id){
        $this->authorize('update', Rules::class);
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
        $paragraph = Rules::find($id);
        return view('evoque.rules.edit', [
            'rules' => $paragraph,
            'changelog' => $paragraph->audits()->with(['user', 'user.member'])->orderBy('created_at', 'desc')->get()
        ]);
    }

    public function add(Request $request){
        $this->authorize('create', Rules::class);
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
        $this->authorize('delete', Rules::class);
        $p = Rules::findOrFail($id);
        $redirect = $p->public ? 'public' : 'private';
        return $p->delete() ?
            redirect()->route('rules', $redirect)->with(['success' => 'Параграф правил успешно удалён!']) :
            redirect()->back()->withErrors([',Возникла ошибка =(']);
    }

    public function changelog(Request $request, $id){
        $this->authorize('viewChangelog', Rules::class);
        $paragraph = Rules::with(['audits' => function($query){
            $query->orderBy('created_at', 'desc');
        }])->find($id);
        return view('evoque.rules.changelog', [
            'paragraph' => $paragraph,
        ]);
    }

}
