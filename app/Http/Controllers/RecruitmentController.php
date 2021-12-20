<?php

namespace App\Http\Controllers;

use App\Application;
use App\Recruitment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecruitmentController extends Controller{

    public function index(Request $request, $id = null){
        $this->authorize('view', Recruitment::class);
        if(isset($id)){
            $app = Recruitment::with(['comments', 'comments.author', 'comments.author.member'])->where('id', $id)->firstOrFail();
            return view('evoque.recruitments.show', [
                'app' => $app
            ]);
        }
        return view('evoque.recruitments.index', [
            'applications' => Recruitment::orderBy('created_at', 'desc')->paginate(15),
            'apps' => Application::where('status', 0)->count()
        ]);
    }

    public function comment(Request $request, $id){
        $application = Recruitment::findOrFail($id);
//        $this->authorize('comment', $application);
        return $application->comments()->create([
            'author_id' => Auth::id(),
            'text' => $request->input('comment'),
            'instance' => 'App\Recruitment',
            'instance_id' => $id,
            'public' => true,
        ]) ?
            redirect()->back()->with(['success' => 'Коментарий сохранен!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function claim($id){
        $application = Recruitment::findOrFail($id);
        $this->authorize('claim', $application);
        $application->status = 3;
        return $application->save() ?
            redirect()->back()->with(['success' => 'Заявка взята в работу!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function accept(Request $request, $id){
        $application = Recruitment::findOrFail($id);
        $this->authorize('claim', $application);
        $application->status = $request->input('accept');
        return $application->save() ?
            redirect()->route('evoque.recruitments')->with(['success' => match($application->status){
                '1' => 'Зявка принята! Для завершения процесса добавления сотрудника на сайт, ему надо вступить в ВТК на сайте TruckersMP.',
                '2' => 'Зявка отклонена!',
                '3' => 'Зявка сохранена!'
            }]) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function delete(Request $request, $id){
        $this->authorize('delete', Recruitment::class);
        $application = Recruitment::findOrFail($id);
        return $application->delete() ?
            redirect()->back()->with(['success' => 'Зявка удалена!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

}
