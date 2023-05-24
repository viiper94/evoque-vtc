<?php

namespace App\Http\Controllers;

use App\Kb;
use App\Rules\NoVk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KbController extends Controller{

    public function index($search = null){
        $kb = Kb::with('user');
        if(!Auth::user()?->member || Auth::user()->cant('viewPrivate', Kb::class)){
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

    public function edit(Request $request, $id = null){
        $kb = $id ? Kb::findOrFail($id) : new Kb();
        $this->authorize(($id ? 'update' : 'create'), $kb);
        if($request->post()){
            $this->validate($request, [
                'title' => 'required|string',
                'category' => 'required|string',
                'article' => ['required', 'string', new NoVk()],
            ]);
            $kb->fill($request->post());
            $kb->visible = $request->input('visible') === 'on';
            $kb->public = $request->input('public') === 'on';
            $kb->author = $kb->author ?? Auth::id();
            if($kb->save()){
                if(!$id) $kb->sort = $kb->id;
                return $kb->save() ?
                    redirect()->route('kb.view', $kb->id)->with(['success' => 'Статья успешно '.($id ? 'отредактирована!' : 'создана!')]) :
                    redirect()->back()->withErrors(['Возникла ошибка =(']);
            }
        }
        return view('kb.edit', [
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

    public function changelog(Request $request, $id){
        $article = Kb::with(['audits' => function($query){
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($id);
        $this->authorize('update', $article);
        return view('kb.changelog', [
            'article' => $article,
        ]);
    }

}
