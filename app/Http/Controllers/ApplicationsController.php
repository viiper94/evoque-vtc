<?php

namespace App\Http\Controllers;

use App\Application;
use App\Comment;
use App\Http\Controllers\Controller;
use App\Member;
use App\Recruitment;
use App\RpStats;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ApplicationsController extends Controller{

    public function app(Request $request, $id = null){
        if(!Auth::user()?->member) abort(404);
        if(isset($id)){
            $app = Application::with('member')->where('id', $id)->firstOrFail();
            $this->authorize('view', $app);
            $rp = null;
            if($app->new_rp_profile) $rp = RpStats::where(['member_id' => $app->member_id, 'game' => $app->new_rp_profile[0]])->first();
            return view('evoque.applications.show', [
                'app' => $app,
                'rp' => $rp
            ]);
        }
        $apps = Application::with('member');
        if(Auth::user()->cant('viewAll', Application::class)){
            $apps = $apps->where('member_id', Auth::user()->member->id);
        }
        return view('evoque.applications.index', [
            'apps' => $apps->orderBy('created_at', 'desc')->paginate(15),
            'recruitments' => Recruitment::where('status', 0)->count()
        ]);
    }

    public function comment(Request $request, $id){
        $application = Application::findOrFail($id);
        $this->authorize('addComment', $application);
        return $application->comments()->create([
            'author_id' => Auth::id(),
            'text' => $request->input('comment'),
            'instance' => 'App\Application',
            'instance_id' => $id,
            'public' => true,
        ]) ?
            redirect()->back()->with(['success' => 'Коментарий сохранен!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function deleteComment(Request $request, $id){
        $comment = Comment::findOrFail($id);
        $application = Application::find($comment->instance_id);
        $this->authorize('deleteComment', [$application, $comment]);
        return $comment->delete() ?
            redirect()->back()->with(['success' => 'Коментарий удалён!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function accept(Request $request, $id){
        $application = Application::with('member', 'member.stats')->where('id', $id)->first();
        $this->authorize('claim', $application);
        $result = true;
        if($request->input('accept') === '1'){
            switch($application->category){
                case 1:
                    $application->member->on_vacation_till = $application->vacation_till;
                    $application->member->vacations += 1;
                    $result = $application->member->save();
                    break;
                case 3:
                    foreach($application->member->stats as $stat){
                        if($stat->game == $application->new_rp_profile[0]){
                            $stat->level = $application->new_rp_profile[1];
                            $result = $stat->save();
                        }
                    }
                    break;
                case 4:
                    $application->member->nickname = $application->new_nickname;
                    $result = $application->member->save();
                    break;
                case 5:
                    if($application->member){
                        $application->member->delete();
                    }
                    break;
            }
        }
        $application->status = $request->input('accept');
        $application->comment = $request->input('comment');
        return $result && $application->save() ?
            redirect()->route('evoque.applications')
                ->with(['success' => 'Зявка '. (match($application->status){
                    '1' => 'принята',
                    '2' => 'отклонена',
                    '3' => 'отредактирована',
                    }) .'!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function delete(Request $request, $id){
        $app = Application::findOrFail($id);
        $this->authorize('delete', $app);
        return $app->delete() ?
            redirect()->route('evoque.applications')->with(['success' => 'Зявка удалена!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function vacation(Request $request){
        $this->authorize('createVacation', Application::class);
        if(!$request->post()) return abort(404);
        $this->validate($request, [
            'vacation_till' => 'required|string',
            'reason' => 'nullable|string'
        ]);
        $app = new Application();
        $app->member_id = Auth::user()->member->id;
        $app->old_nickname = Auth::user()->member->nickname;
        $app->category = 1;
        $app->vacation_till = [
            'from' => explode(' - ', $request->input('vacation_till'))[0],
            'to' => explode(' - ', $request->input('vacation_till'))[1],
        ];
        $app->reason = $request->input('reason');
        return $app->save() ?
            redirect()->route('evoque.applications')->with(['success' => 'Зявка успешно подана!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function rp(Request $request){
        $this->authorize('create', Application::class);
        if(!$request->post()) return abort(404);
        $this->validate($request, [
            'new_rp_profile' => 'required|numeric',
            'reason' => 'nullable|string'
        ]);
        $app = new Application();
        $app->member_id = Auth::user()->member->id;
        $app->old_nickname = Auth::user()->member->nickname;
        $app->new_rp_profile = [$request->input('game'), $request->input('new_rp_profile')];
        $app->category = 3;
        $app->reason = $request->input('reason');
        return $app->save() ?
            redirect()->route('evoque.applications')->with(['success' => 'Зявка успешно подана!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function nickname(Request $request){
        $this->authorize('create', Application::class);
        if(!$request->post()) return abort(404);
        $this->validate($request, [
            'new_nickname' => 'required|string',
            'reason' => 'nullable|string'
        ]);
        $app = new Application();
        $app->member_id = Auth::user()->member->id;
        $app->old_nickname = Auth::user()->member->nickname;
        $app->new_nickname = $request->input('new_nickname');
        $app->category = 4;
        $app->reason = $request->input('reason');
        return $app->save() ?
            redirect()->route('evoque.applications')->with(['success' => 'Зявка успешно подана!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function fire(Request $request){
        if(!$request->post()) return abort(404);
        $this->authorize('create', Application::class);
        $this->validate($request, [
            'fire' => 'accepted',
            'reason' => 'nullable|string'
        ]);
        $app = new Application();
        $app->member_id = Auth::user()->member->id;
        $app->old_nickname = Auth::user()->member->nickname;
        $app->category = 5;
        $app->reason = $request->input('reason');
        return $app->save() ?
            redirect()->route('evoque.applications')->with(['success' => 'Зявка успешно подана!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

}
