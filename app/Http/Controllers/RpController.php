<?php

namespace App\Http\Controllers;

use App\Member;
use App\Role;
use App\RpReport;
use App\RpReward;
use App\RpStats;
use App\Rules\RpLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RpController extends Controller{

    public function index(){
        if(!Auth::user()?->member) abort(404);
        return view('evoque.rp.index', [
            'roles' => Role::with([
                    'members',
                    'members.role' => function($query){
                        $query->where('visible', '1');
                    },
                    'members.stats' => function($query){
                        $query->whereNotNull('level');
                    },
                    'members.stats.rewards'
                ])->where('visible', 1)->get()->groupBy('group')->filter(function($group){
                    foreach($group as $role){
                        foreach($role->members as $member){
                            foreach($member->stats as $stat){
                                $has = $stat && $member->topRole() == $role->id;
                            }
                            if($has) return true;
                        }
                    }
                    return false;
                }),
            'rewards' => RpReward::all()->groupBy('game')
        ]);
    }

    public function weekly(){
        if(!Auth::user()?->member) abort(404);
        return view('evoque.rp.weekly', [
            'stats' => RpStats::selectRaw(
                'rp_stats.*,
                    ats.distance as ats_distance,
                    ats.distance_total as ats_distance_total,
                    ats.bonus as ats_bonus,
                    (rp_stats.distance+ats.distance) as sum_distance,
                    (rp_stats.quantity+ats.quantity) as sum_quantity,
                    (rp_stats.weight+ats.weight) as sum_weight,
                    (rp_stats.bonus+ats.bonus) as sum_bonus')
                ->with('member')->where('rp_stats.game', 'ets2')
                ->leftJoin('rp_stats as ats', function($join){
                    $join->on('rp_stats.member_id', '=', 'ats.member_id')
                        ->where('ats.game', '=', 'ats');
                })
                ->orderByRaw('(sum_distance+sum_bonus) desc')->orderBy('rp_stats.id')->get()
        ]);
    }

    public function reports(){
        if(!Auth::user()?->member) abort(404);
        $reports = RpReport::with('member');
        if(Auth::user()->cant('viewAll', RpReport::class)) $reports->where('member_id', Auth::user()->member->id);
        return view('evoque.rp.reports', [
            'reports' => $reports->orderBy('created_at', 'desc')->paginate(24)
        ]);
    }

    public function addReport(Request $request){
        $this->authorize('create', RpReport::class);
        $report = new RpReport();
        if($request->post()){
            $this->validate($request, [
                'start-screen' => 'required|image|max:6000',
                'finish-screen' => 'required|image|max:6000',
                'new-id-screen' => 'nullable|image|max:6000',
                'game' => 'required|string',
                'note' => 'nullable|string'
            ]);
            $report->fill($request->post());
            $images = array();
            foreach($request->files as $file){
                $name = md5(time().$file->getClientOriginalName()).'.'. $file->getClientOriginalExtension();
                $file->move(public_path('/images/rp/'), $name);
                $images[] = $name;
            }
            $report->images = $images;
            $report->member_id = Auth::user()->member->id;
            return $report->save() ?
                redirect()->route('evoque.rp.reports')->with(['success' => 'Отчёт успешно отправлен на модерацию!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('evoque.rp.add', [
            'report' => $report,
            'stats' => RpStats::where('member_id', Auth::user()->member->id)->get()
        ]);
    }

    public function deleteReport(Request $request, $id){
        $report = RpReport::findOrFail($id);
        $this->authorize('delete', $report);
        $report->deleteImages(public_path('/images/rp/'));
        return $report->delete() ?
            redirect()->route('evoque.rp.reports')->with(['success' => 'Отчёт успешно удалён!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function viewReport(Request $request, $id){
        $report = RpReport::with('member')->where('id', $id)->first();
        $this->authorize('claim', $report);
        if($request->post()){
            $this->validate($request, [
                'distance' => 'required_with:accept|nullable|numeric',
                'weight' => 'required_with:accept|nullable|numeric',
                'level' => [new RpLevel('level_promods'), 'nullable', 'numeric'],
                'level_promods' => [new RpLevel('level'), 'nullable', 'numeric'],
                'comment' => 'nullable|string',
            ]);
            $accept = $request->input('accept') ?? false;
            $decline = $request->input('decline') ?? false;
            $stat = RpStats::where(['member_id' => $report->member_id, 'game' => $report->game])->first();
            $result = true;
            if($accept && !$decline){
                if(is_null($stat)){
                    $stat = new RpStats();
                    $stat->member_id = $report->member_id;
                }
                $stat->distance += $request->input('distance');
                $stat->weight += $request->input('weight');
                $stat->bonus += $request->input('bonus');
                if($request->input('level') && !$request->input('level_promods')){
                    $stat->level = $request->input('level');
                }
                if(!$request->input('level') && $request->input('level_promods')){
                    $stat->level_promods = $request->input('level_promods');
                }
                $stat->game = $report->game;
                $stat->quantity += 1;
                $report->status = 1;
                $result = $stat->save();
            }elseif($decline && ! $accept){
                $report->status = 2;
            }
            if($request->input('comment')){
                $report->comment = $request->input('comment');
                $report->comment_by = Auth::user()->member->nickname;
            }
            if($result && $report->save()) return redirect()->route('evoque.rp.reports', $report->game)
                ->with(['success' => 'Отчёт '.($accept ? 'принят' : 'отклонён').'!']);
            return redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('evoque.rp.view', [
            'report' => $report,
            'stat' => RpStats::with('member')->where(['member_id' => $report->member->id, 'game' => $report->game])->first()
        ]);
    }

    public function editStat(Request $request, $id){
        $this->authorize('updateRpStats', Member::class);
        $stat = RpStats::findOrFail($id);
        $this->validate($request, [
            'distance' => 'nullable|numeric',
            'level' => 'nullable|numeric',
            'level_promods' => 'nullable|numeric',
            'bonus' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'distance_total' => 'nullable|numeric',
            'weight_total' => 'nullable|numeric',
            'quantity' => 'nullable|numeric',
            'quantity_total' => 'nullable|numeric',
        ]);
        $stat->fill($request->post());
        return $stat->save() ?
            redirect()->route('evoque.rp', $stat->game)->with(['success' => 'Статистика успешно отредактирована!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function results(){
        return view('evoque.rp.results', [
            'results' => []
        ]);
    }

    public function createResults(){
        $this->authorize('resetStats', RpReport::class);
        $stats = RpStats::where('distance', '>', '0')->get();
        foreach($stats as $stat){
            $stat->quantity_total += $stat->quantity;
            $stat->distance_total = $stat->distance_total + $stat->distance + $stat->bonus;
            $stat->weight_total += $stat->weight;
            $stat->quantity = 0;
            $stat->bonus = 0;
            $stat->distance = 0;
            $stat->weight = 0;
            $stat->save();
        }
        return redirect()->back()->with(['success' => 'Готово!']);
    }

}
