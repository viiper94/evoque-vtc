<?php

namespace App\Http\Controllers;

use App\Member;
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
            'first_answered' => $results->sortBy('created_at')->first(),
            'count' => TestQuestion::count(),
            'question' => $question_number ? $questions[$question_number] : null,
            'view' => !$question_number ? (Auth::user()->member->hasCompleteTest() ? 'result' : 'start') : 'question'
        ]);
    }

    public function manage(){
        $this->authorize('accessToEditPage', TestQuestion::class);
        return view('evoque.test.manage', [
            'questions' => TestQuestion::orderBy('sort', 'asc')->get()
        ]);
    }

    public function edit(Request $request, $id = null){
        $this->authorize($id ? 'update' : 'create', TestQuestion::class);
        $question = $id ? TestQuestion::findOrFail($id) : new TestQuestion();
        if($request->post()){
            $this->validate($request, [
                'question' => 'string|required',
                'answers' => 'array|required|max:4|min:2',
                'answers.*' => 'string|required',
                'correct' => 'numeric|required',
            ]);
            $question->fill($request->post());
            if(!$id) $question->sort = intval($this->getLatestSortId(TestQuestion::class)) + 1;
            return $question->save() && TestResult::whereQuestionId($id)->delete() !== false ?
                redirect()->route('evoque.test.manage')->with(['success' => 'Вопрос успешно '.($id ? 'изменён!' : 'добавлен!')]) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('evoque.test.edit', [
            'question' => $question
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
        return $question->delete() && TestResult::whereQuestionId($id)->delete() !== false && TestQuestion::resort() ?
            redirect()->back()->with(['success' => 'Вопрос успешно удалён!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function results(){
        $this->authorize('view', TestResult::class);
        $total = TestQuestion::count();
        $test_results = TestResult::with(['question', 'member' => function($q){
            $q->withTrashed();
        }])
            ->where('created_at', '>', \Carbon\Carbon::now()->subMonth()->format('Y-m-d H:i'))
            ->get();
        // sorting results by member
        $results = array();
        foreach($test_results as $item){
            if($item->member){
                $results[$item->member->nickname][] = $item;
            }
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
            $data[$nickname]['id'] = $answers[0]->member->id;
            $data[$nickname]['correct'] = $correct;
            $data[$nickname]['last'] = $last->format('d.m.Y');
            $data[$nickname]['count'] = count($answers);
            $data[$nickname]['complete'] = $total === count($answers);
        }

        return view('evoque.test.results', [
            'results' => $data,
            'total' => $total
        ]);
    }

    public function memberResults($id){
        $this->authorize('view', TestResult::class);
        $results = TestResult::with('question')->whereMemberId($id)
            ->where('created_at', '>', \Carbon\Carbon::now()->subMonth()->format('Y-m-d H:i'))
            ->get();
        return view('evoque.test.member_results', [
            'results' => $results,
            'member' => Member::withTrashed()->findOrFail($id)
        ]);
    }

    public function deleteResults(Request $request, $id){
        $this->authorize('delete', TestResult::class);
        return TestResult::whereMemberId($id)->delete() ?
            redirect()->route('evoque.test.results')->with(['success' => 'Результаты успешно удалёны!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

}
