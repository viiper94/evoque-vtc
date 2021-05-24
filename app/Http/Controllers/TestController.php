<?php

namespace App\Http\Controllers;

use App\TestQuestion;
use Illuminate\Http\Request;

class TestController extends Controller{

    public function index(){
        return view('evoque.test.index');
    }

    public function add(Request $request){
        if($request->post()){
//            dd($request->post());
            $this->validate($request, [
                'question' => 'string|required',
                'answers' => 'array|required|max:4|min:2',
                'answers.*' => 'string|required',
                'correct' => 'numeric|required',
            ]);
            $question = new TestQuestion();
            $question->fill($request->post());
            return $question->save() ?
                redirect()->route('evoque.test.edit')->with(['success' => 'Вопрос успешно добавлен!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('evoque.test.edit', [
            'question' => new TestQuestion()
        ]);
    }

    public function edit(Request $request, $id = null){
        return view('evoque.test.manage', [
            'questions' => TestQuestion::all()
        ]);
    }

}
