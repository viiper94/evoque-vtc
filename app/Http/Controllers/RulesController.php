<?php

namespace App\Http\Controllers;

use App\Rules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class RulesController extends Controller{

    public function index($type = 'public'){
        if(!Auth::user()?->member) $type = 'public';
        return view('rules', [
            'rules' => Rules::with('audits')->where('public', $type === 'public' ? '1' : '0')->orderBy('paragraph')->get(),
            'public' => $type === 'public'
        ]);
    }

    public function edit(Request $request, $id = null){
        $this->authorize($id ? 'update' : 'create', Rules::class);
        $paragraph = $id ? Rules::findOrFail($id) : new Rules();
        if($request->post()){
            $this->validate($request, [
                'paragraph' => 'required|numeric',
                'title' => 'required|string',
                'text' => 'required',
            ]);
            $paragraph->fill($request->post());
            $paragraph->public = $request->input('public') == 'true';
            return $paragraph->save() ?
                redirect()->route('rules', $paragraph->public ? 'public' : 'private')
                    ->with(['success' => 'Параграф правил успешно '.($id ? 'изменён!' : 'создан!')]) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('evoque.rules.edit', [
            'rules' => $paragraph
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
