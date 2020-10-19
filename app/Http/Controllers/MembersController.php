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
        if(Auth::guest()) return redirect()->route('auth.steam');
        return view('evoque.members.index', [
            'roles' => Role::with(['members' => function($query){
                $query->where('visible', 1)->orderBy('sort', 'desc')->orderBy('scores', 'desc')->orderBy('join_date', 'asc');
            }, 'members.user', 'members.role' => function($query){
                $query->where('visible', '1');
            }])->where('visible', 1)->get()->groupBy('group')
        ]);
    }

    public function edit(Request $request, $id){
        if(Gate::denies('manage_table')) abort(403);
        if($request->post()){
            $this->validate($request, [
                'nickname' => 'required|string',
                'join_date' => 'required|date_format:d.m.Y',
                'convoys' => 'required|numeric',
                'vacations' => 'required|numeric'
            ]);
            // TODO filter photo links
            $member = Member::findOrFail($id);
            $member->fill($request->post());
            $member->visible = $request->input('visible') === 'on';
            $member->sort = $request->input('sort') === 'on';
            $member->join_date = Carbon::parse($request->input('join_date'))->format('Y-m-d');
            $member->on_vacation_till = $request->input('on_vacation_till') ? Carbon::parse($request->input('on_vacation_till'))->format('Y-m-d') : null;
            $member->role()->detach();
            foreach($request->input('roles') as $role){
                $member->role()->attach($role);
            }
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
        if(Gate::denies('manage_members')) abort(403);
        $member = Member::with(['user', 'role'])->where('id', $id)->first();
        $member->role()->detach();
        $member->user->remember_token = null;
        $member->user->save();
        return $member->delete() ?
            redirect()->route('evoque.members')->with(['success' => 'Сотрудник уволен!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function add(Request $request){
        if($request->ajax() && Gate::allows('manage_table')){
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
                $convoys = $member->convoys;
                $member->convoys += 1;
                return response()->json([
                    'scores' => $member->save() ? $member->convoys : $convoys,
                    'status' => 'OK'
                ]);
            }
        }
        return false;
    }

}
