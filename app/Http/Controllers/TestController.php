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
            $this->validate($request, [
                'question' => 'string|required',
                'answers' => 'array|required|max:4|min:2',
                'answers.*' => 'string|required',
                'correct' => 'numeric|required',
            ]);
            $question = new TestQuestion();
            $question->fill($request->post());
            $question->sort = intval($this->getLatestSortId(TestQuestion::class)) + 1;
            return $question->save() ?
                redirect()->route('evoque.test.edit')->with(['success' => 'Вопрос успешно добавлен!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('evoque.test.edit', [
            'question' => new TestQuestion()
        ]);
    }

    public function edit(Request $request, $id = null){
        if($id){
            $question = TestQuestion::findOrFail($id);
            if($request->post()){
                $this->validate($request, [
                    'question' => 'string|required',
                    'answers' => 'array|required|max:4|min:2',
                    'answers.*' => 'string|required',
                    'correct' => 'numeric|required',
                ]);
                $question->fill($request->post());
                return $question->save() ?
                    redirect()->route('evoque.test.edit')->with(['success' => 'Вопрос успешно изменён!']) :
                    redirect()->back()->withErrors(['Возникла ошибка =(']);
            }
            return view('evoque.test.edit', [
                'question' => $question
            ]);
        }
        return view('evoque.test.manage', [
            'questions' => TestQuestion::orderBy('sort', 'asc')->get()
        ]);
    }

    public function sort(Request $request, $id, $direction){
        if(!isset($id)) abort(404);
        $question = TestQuestion::findOrFail($id);
        if($direction === 'up') $next_question = TestQuestion::where('sort', '<', $question->sort)->orderBy('sort', 'desc')->first();
        else $next_question = TestQuestion::where('sort', '>', $question->sort)->orderBy('sort', 'asc')->first();
        if(!$next_question) return redirect()->back();
        return $this->swapSort($question, $next_question)  ?
            redirect()->back()->with(['success' => 'Вопрос успешно изменён!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function delete(Request $request, $id){
        $question = TestQuestion::findOrFail($id);
//        $this->authorize('delete', $report);
        return $question->delete() ?
            redirect()->route('evoque.test.edit')->with(['success' => 'Вопрос успешно удалён!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

}
