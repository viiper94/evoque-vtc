<?php

namespace App\Http\Controllers;

use App\Kb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KbController extends Controller{

    public function index($search = null){
        $kb = Kb::with('user');
        if(Auth::guest()){
            $kb = $kb->where('public', '1');
        }
        if(Auth::user()->cant('viewAny', Kb::class)){
            $kb = $kb->where('visible', '1');
        }
        $kb = $kb->orderBy('sort', 'desc')->get();
        return view('kb.index', [
            'categories' => $kb->groupBy('category')
        ]);
    }

    public function add(Request $request){
        if($request->post()){
            $this->validate($request, [
                'title' => 'required|string',
                'category' => 'required|string',
                'article' => 'required|string',
            ]);
            $kb = new Kb();
            $kb->fill($request->post());
            $kb->visible = $request->input('visible') === 'on';
            $kb->public = $request->input('public') === 'on';
            $kb->author = Auth::id();
            return $kb->save() ?
                redirect()->route('kb')->with(['success' => 'Статья успешно создана!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('kb.add', [
            'kb' => new Kb()
        ]);
    }

}
