<?php

namespace App\Http\Controllers;

use App\Kb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KbController extends Controller{

    public function index($search = null){
        $kb = Kb::with('user');
        if(!Auth::user()->member || Auth::user()->cant('viewPrivate', Kb::class)){
            $kb = $kb->where('public', '1');
        }elseif(Auth::user()->cant('viewAny', Kb::class)){
            $kb = $kb->where('visible', '1');
        }
        $kb = $kb->orderBy('sort')->get();
        return view('kb.index', [
            'categories' => $kb->groupBy('category')
        ]);
    }

    public function article(Request $request, $id){
        $kb = Kb::with(['user', 'user.member'])->where('id', $id)->firstOrFail();
        return view('kb.article', [
            'article' => $kb
        ]);
    }

    public function add(Request $request){
        if($request->post()){
            $this->authorize('create', Kb::class);
            $this->validate($request, [
                'title' => 'required|string',
                'category' => 'required|string',
                'article' => 'required|string|no_vk',
            ]);
            $kb = new Kb();
            $kb->fill($request->post());
            $kb->visible = $request->input('visible') === 'on';
            $kb->public = $request->input('public') === 'on';
            $kb->author = Auth::id();
            if($kb->save()){
                $kb->sort = $kb->id;
                return $kb->save() ?
                    redirect()->route('kb')->with(['success' => 'Статья успешно создана!']) :
                    redirect()->back()->withErrors(['Возникла ошибка =(']);
            }
        }
        return view('kb.add', [
            'kb' => new Kb()
        ]);
    }

    public function edit(Request $request, $id){
        $kb = Kb::findOrFail($id);
        $this->authorize('update', $kb);
        if($request->post()){
            $this->validate($request, [
                'title' => 'required|string',
                'category' => 'required|string',
                'article' => 'required|string|no_vk',
            ]);
            $kb->fill($request->post());
            $kb->visible = $request->input('visible') === 'on';
            $kb->public = $request->input('public') === 'on';
            return $kb->save() ?
                redirect()->route('kb')->with(['success' => 'Статья успешно отредактирована!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('kb.add', [
            'kb' => $kb
        ]);
    }

    public function delete(Request $request, $id){
        $kb = Kb::findOrFail($id);
        $this->authorize('delete', $kb);
        return $kb->delete() ?
            redirect()->route('kb')->with(['success' => 'Статья успешно удалена!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

}
