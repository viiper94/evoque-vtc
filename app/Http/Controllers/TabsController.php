<?php

namespace App\Http\Controllers;

use App\Member;
use App\Role;
use App\Tab;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TabsController extends Controller{

    public function index(){
        if(!Auth::user()?->member) abort(404);
        $tabs = Tab::with(['member', 'lead']);
        if(Auth::user()->cant('viewAny', Tab::class)){
            $tabs = $tabs->where('member_id', Auth::user()->member->id);
        }
        return view('evoque.convoys.tab.index', [
            'tabs' => $tabs->orderBy('date', 'desc')->paginate(8)
        ]);
    }

    public function add(Request $request){
        $this->authorize('create', Tab::class);
        $tab = new Tab();
        if($request->post()){
            $this->validate($request, [
                'convoy_title' => 'required|string',
                'lead_id' => 'required|numeric',
                'date' => 'required|date',
                'screenshot' => 'required|image',
                'description' => 'nullable|string',
            ]);
            $tab->fill($request->post());
            $tab->description = htmlentities(trim($request->input('description')));
            $tab->member_id = Auth::user()->member->id;
            $tab->date = $request->input('date');
            if($request->file('screenshot')){
                $tab->screenshot = $tab->saveImage($request->file('screenshot'), '/images/convoys/tab/');
            }
            return $tab->save() ?
                redirect()->route('evoque.convoys.tab')->with(['success' => 'Скрин TAB успешно подан!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        $tab->date = Carbon::today();
        return view('evoque.convoys.tab.edit', [
            'tab' => $tab,
            'members' => Member::where('visible', '1')->get()
        ]);
    }

    public function edit(Request $request, $id){
        $tab = Tab::with(['member', 'lead'])->where('id', $id)->first();
        $this->authorize('update', $tab);
        if($request->post()){
            $this->validate($request, [
                'convoy_title' => 'required|string',
                'lead_id' => 'required|numeric',
                'date' => 'required|date',
                'screenshot' => 'nullable|image',
            ]);
            $tab->fill($request->post());
            $tab->description = htmlentities(trim($request->input('description')));
            $tab->date = Carbon::parse($request->input('date'))->format('Y-m-d');
            if($request->file('screenshot')){
                if(is_file(public_path('/images/convoys/tab/').$tab->screenshot)){
                    unlink(public_path('/images/convoys/tab/').$tab->screenshot);
                }
                $tab->screenshot = $tab->saveImage($request->file('screenshot'), '/images/convoys/tab/');
            }
            return $tab->save() ?
                redirect()->route('evoque.convoys.tab')->with(['success' => 'Скрин TAB успешно отредактирован!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('evoque.convoys.tab.edit', [
            'tab' => $tab,
            'members' => Member::where('visible', '1')->get()
        ]);
    }

    public function delete(Request $request, $id){
        $this->authorize('delete', Tab::class);
        $tab = Tab::findOrFail($id);
        if(is_file(public_path('/images/convoys/tab/').$tab->screenshot)){
            unlink(public_path('/images/convoys/tab/').$tab->screenshot);
        }
        return $tab->delete() ?
            redirect()->route('evoque.convoys.tab')->with(['success' => 'Скрин TAB успешно удалён!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function accept(Request $request, $id){
        $tab = Tab::with(['member', 'lead'])->where(['id' => $id, 'status' => 0])->firstOrFail();
        $this->authorize('claim', $tab);
        if($request->post()){
            $this->validate($request, [
                'scores' => 'nullable|array',
                'lead' => 'nullable|string',
                'comment' => 'nullable|string'
            ]);
            $lead = explode(',', $request->input('lead'));
            if($request->input('scores') && $request->input('accept') === 1){
                foreach($request->input('scores') as $member_id => $value){
                    $member = Member::with('role')->find($member_id);
                    $member->convoys += 1;
                    if($member->isTrainee()){
                        if($member->trainee_convoys === 3){
                            $member->role()->detach(14);
                            $member->role()->attach(13);
                        }else{
                            $member->trainee_convoys += 1;
                        }
                    }
                    if(isset($member->scores)) $member->scores += $value;
                    $member->checkRoles();
                    if(isset($member->money) && $lead[0] == $member->id) $member->money += $lead[1];
                    $member->save();
                }
            }
            $tab->status = $request->input('accept');
            $tab->comment = $request->input('comment');
            return $tab->save() ?
                redirect()->route('evoque.convoys.tab')->with(['success' => 'Скрин TAB успешно отредактирован!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('evoque.convoys.tab.accept', [
            'tab' => $tab,
            'members' => Member::where('visible', '1')->orderBy('nickname')->get(),
            'roles' => Role::with([
                'members' => function($query){
                    $query->where('visible', 1)->orderBy('sort', 'desc')->orderBy('scores', 'desc')->orderBy('join_date', 'asc');
                },
                'members.role' => function($query){
                    $query->where('visible', '1');
                }
            ])->where('visible', 1)->get()->groupBy('group')->filter(function($roles){
                foreach($roles as $role){
                    foreach($role->members as $member){
                        $has = $member->topRole() === $role->id;
                        if($has) return true;
                    }
                }
                return false;
            })
        ]);
    }

}
