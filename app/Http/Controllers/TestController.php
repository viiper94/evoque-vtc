<?php

namespace App\Http\Controllers;

use App\TestQuestion;
use App\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller{

    public function index(Request $request, int $question_number = null){
        $this->authorize('do', TestResult::class);
        $questions = TestQuestion::all()->keyBy('sort');
        if($request->post() && $request->input('answer') !== null){
            $prev_question = $questions[$request->input('sort')];
            $result = new TestResult();
            $result->member_id = Auth::user()->member->id;
            $result->question_id = $prev_question->id;
            $result->answer = $request->input('answer');
            $result->correct = $request->input('answer') == $prev_question->correct;
            $result->save();
        }
        $results = TestResult::with('question')->whereMemberId(Auth::user()->member->id)->get()->keyBy('question.sort');
        return view('evoque.test.index', [
            'results' => $results,
            'correct' => $results->filter(function($value){
                return $value->correct;
            }),
            'count' => TestQuestion::count(),
            'question' => $question_number ? $questions[$question_number] : null,
            'view' => !$question_number ? (Auth::user()->member->hasCompleteTest() ? 'result' : 'start') : 'question',
            'question_number' => $question_number
        ]);
    }

    public function add(Request $request){
        $this->authorize('create', TestQuestion::class);
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
            $this->authorize('update', TestQuestion::class);
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
        $this->authorize('accessToEditPage', TestQuestion::class);
        return view('evoque.test.manage', [
            'questions' => TestQuestion::orderBy('sort', 'asc')->get()
        ]);
    }

    public function sort(Request $request, $id, $direction){
        $this->authorize('update', TestQuestion::class);
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
        $this->authorize('delete', TestQuestion::class);
        $question = TestQuestion::findOrFail($id);
        return $question->delete() ?
            redirect()->route('evoque.test.edit')->with(['success' => 'Вопрос успешно удалён!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function results(){
        $total = TestQuestion::count();
        $test_results = TestResult::with(['question', 'member'])->get();

        // sorting results by member
        $results = array();
        foreach($test_results as $item){
            $results[$item->member->nickname][] = $item;
        }

        // parsing data
        $data = array();
        foreach($results as $nickname => $answers){
            $correct = 0;
            $last = Carbon::create(2020, 12, 31, 00, 00);
            foreach($answers as $answer){
                $correct += $answer->correct ? 1 : 0;
                $last = $last->gte($answer->created_at) ? $last : $answer->created_at;
            }
            $data[$nickname]['correct'] = $correct;
            $data[$nickname]['last'] = $last->format('d.m.Y');
            $data[$nickname]['count'] = count($answers);
            $data[$nickname]['complete'] = $total === count($answers);
        }

        return view('evoque.test.results', [
            'results' =>$data
        ]);
    }

}
