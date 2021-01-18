<?php

namespace App\Http\Controllers;


use App\Member;
use App\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class MembersController extends Controller{

    public function index(){
        if(Auth::guest() || !Auth::user()->member) abort(404);
        return view('evoque.members.index', [
            'roles' => Role::with([
                'members' => function($query){
                                $query->where('visible', 1)->orderBy('sort', 'desc')->orderBy('scores', 'desc')->orderBy('join_date', 'asc');
                            },
                'members.user',
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

    public function edit(Request $request, $id){
        if(Auth::user()->cant('update', Member::class) && Auth::user()->cant('updateRpStats', Member::class)) abort(403);
        if($request->post()){
            $this->validate($request, [
                'nickname' => 'required|string',
                'join_date' => 'required|date_format:d.m.Y',
                'convoys' => 'required|numeric',
                'vacations' => 'required|numeric',
                'plate' => 'nullable|string'
            ]);
            $member = Member::with('role')->findOrFail($id);
            $this->authorize('update', Member::class);
            $member->fill($request->post());
            $member->visible = $request->input('visible') === 'on';
            $money = $request->input('money');
            $member->money = isset($money) ? str_replace(',', '.', $request->input('money')) : null;
            $member->sort = $request->input('sort') === 'on';
            $member->join_date = Carbon::parse($request->input('join_date'))->format('Y-m-d');
            $member->trainee_until = $request->input('trainee_until') ? Carbon::parse($request->input('trainee_until'))->format('Y-m-d') : null;
            $member->on_vacation_till = $request->input('on_vacation_till') ? Carbon::parse($request->input('on_vacation_till'))->format('Y-m-d') : null;
            if(Auth::user()->can('updateRoles', $member)){
                $member->role()->detach();
                foreach($request->input('roles') as $role){
                    $member->role()->attach($role);
                }
            }
            $member->checkRoles();
            return $member->save() ?
                redirect()->route('evoque.members')->with(['success' => 'Сотрудник успешно отредактирован!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('evoque.members.edit', [
            'member' => Member::with(['role', 'user', 'audits' => function($query){
                $query->limit(10)->orderBy('created_at', 'desc');
            }, 'audits.user.member', 'stats'])->where('id', $id)->first(),
            'roles' => Role::all()
        ]);
    }

    public function fire(Request $request, $id){
        $member = Member::with(['user', 'role'])->where('id', $id)->first();
        $this->authorize('fire', $member);
        $member->role()->detach();
        $member->user->remember_token = null;
        $member->user->fired_at = Carbon::now();
        $member->user->save();
        return $member->delete() ?
            redirect()->route('evoque.members')->with(['success' => 'Сотрудник уволен!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function add(Request $request){
        if($request->ajax()){
            $this->authorize('setActivity', Member::class);
            $member = Member::find($request->input('member'));
            if($request->input('target') === 'бал'){
                $scores = $member->scores;
                $member->scores += 1;
                return response()->json([
                    'scores' => $member->save() ? $member->scores : $scores,
                    'status' => 'OK'
                ]);
            }elseif($request->input('target') === 'эвика'){
                $money = $member->money;
                $member->money += 0.5;
                return response()->json([
                    'scores' => $member->save() ? $member->money : $money,
                    'status' => 'OK'
                ]);
            }elseif($request->input('target') === 'посещение'){
                if($member->isTrainee()){
                    $convoys = $member->trainee_convoys;
                    $member->trainee_convoys += 1;
                    $member->convoys += 1;
                }else{
                    $convoys = $member->convoys;
                    $member->convoys += 1;
                }
                return response()->json([
                    'scores' => $member->save() ? ($member->isTrainee() ? $member->trainee_convoys : $member->convoys ) : $convoys,
                    'status' => 'OK'
                ]);
            }
        }
        return false;
    }

    public function resetConvoys(Request $request){
        if(!$request->ajax() || Auth::user()->cant('resetActivity', Member::class)) abort(403);
        $members = Member::with('role')->where('convoys', '>', 0)->get();
        foreach($members as $member){
            $member->convoys = 0;
            $member->save();
        }
        return response()->json([
            'status' => 'OK'
        ]);
    }

}
