<?php

namespace App\Http\Controllers;


use App\Member;
use App\Role;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class MembersController extends Controller{

    public function index(){
        if(!Auth::user()?->member) abort(404);
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
        if(!User::orCan(['update', 'updateRpStats'], Member::class)) abort(403);
        if($request->post()){
            $this->validate($request, [
                'nickname' => 'required|string',
                'join_date' => 'required|date_format:d.m.Y',
                'convoys' => 'required|numeric',
                'vacations' => 'required|numeric',
                'plate' => 'nullable|string'
            ]);
            $member = Member::with('role')->withTrashed()->findOrFail($id);
            $this->authorize('update', Member::class);
            $member->fill($request->post());
            $member->visible = $request->input('visible') === 'on' ? 1 : 0;
            $money = $request->input('money');
            $member->money = isset($money) ? str_replace(',', '.', $request->input('money')) : null;
            $member->sort = $request->input('sort') === 'on' ? 1 : 0;
            $member->join_date = Carbon::parse($request->input('join_date'))->format('Y-m-d');
            $member->trainee_until = $request->input('trainee_until') ? Carbon::parse($request->input('trainee_until'))->format('Y-m-d') : null;
            $member->on_vacation_till = $request->input('on_vacation_till') ? [
                'from' => explode(' - ', $request->input('on_vacation_till'))[0],
                'to' => explode(' - ', $request->input('on_vacation_till'))[1],
            ] : null;
            if(Auth::user()->can('updateRoles', $member)){
                $member->role()->sync($request->input('roles'));
            }
            $member->checkRoles();
            if(Auth::user()->can('updatePersonalInfo', Member::class)){
                $member->user->name = $request->input('name');
                $member->user->city = $request->input('city');
                $member->user->country = $request->input('country');
                $member->user->birth_date = Carbon::parse($request->input('birth_date'))->format('Y-m-d');
                $member->user->vk = $request->input('vk');
                $member->user->save();
            }
            return $member->save() ?
                redirect()->route('evoque.members')->with(['success' => 'Сотрудник успешно отредактирован!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        $member = Member::with(['role', 'user', 'audits' => function($query){
            $query->limit(10)->orderBy('created_at', 'desc');
        }, 'audits.user', 'audits.user.member' => function($query){
            $query->withTrashed();
        }, 'stats'])->where('id', $id)->withTrashed()->firstOrFail();
        return view('evoque.members.edit', [
            'member' => $member,
            'roles' => Role::all(),
            'rolePermissions' => $member->getRolesPermissions()
        ]);
    }

    public function fire(Request $request, $id, $restore = false){
        $member = Member::with(['user', 'role'])->where('id', $id)->first();
        $this->authorize('fire', $member);
        if($restore === 'soft'){
            $member->restore = true;
            $member->save();
        }
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
                $member->checkRoles();
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

    public function trash(Request $request){
        $this->authorize('restore', Member::class);
        if($request->input('q')){
            $members = Member::select(['members.*', 'users.name', 'users.vk', 'users.steamid64', 'users.discord_name', 'users.truckersmp_id'])
                ->rightJoin('users', function($query) use ($request){
                    $query->on('users.id', '=', 'members.user_id')
                        ->where(function($query) use ($request){
                            $query->orWhere('name', 'like', '%'.$request->input('q').'%')
                                ->orWhere('nickname', 'like', '%'.$request->input('q').'%')
                                ->orWhere('vk', 'like', '%'.$request->input('q').'%')
                                ->orWhere('steamid64', 'like', '%'.$request->input('q').'%')
                                ->orWhere('discord_name', 'like', '%'.$request->input('q').'%')
                                ->orWhere('truckersmp_id', 'like', '%'.$request->input('q').'%');
                        });
                    });
        }else{
            $members = Member::with('user');
        }
        return view('evoque.members.trash', [
            'members' => $members->onlyTrashed()->orderBy('deleted_at', 'desc')->paginate(20)
        ]);
    }

    public function restore(Request $request, $id){
        $this->authorize('restore', Member::class);
        $member = Member::withTrashed()->with('user')->where('id', $id)->first();
        return $member->restore() && $member->user->update(['fired_at' => null]) ?
            redirect()->route('evoque.members')->with(['success' => 'Сотрудник восстановлен!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function changelog(Request $request, $id){
        $this->authorize('update', Member::class);
        return view('evoque.members.changelog', [
            'member' => Member::withTrashed()->with(['audits' => function($query){
                $query->orderBy('created_at', 'desc');
            }])->find($id)
        ]);
    }

    public function weekly(){
        if(!Auth::user()?->member) abort(404);
        return view('evoque.members.weekly', [
            'members' => Member::with(['user', 'role' => function($query){
                $query->where('visible', '1');
            }])->orderBy('convoys', 'desc')->orderBy('sort', 'desc')->orderBy('scores', 'desc')->get()
        ]);
    }

    public function editPermissions(Request $request, $id){
        $member = Member::find($id);
        $this->authorize('updatePermissions', $member);
        if(!$request->post()) abort(404);
        $member->permissions = $request->post('permissions') ?? null;
        return $member->save() ?
            redirect()->route('evoque.members')->with(['success' => 'Права сотрудника успешно отредактированы!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

}
