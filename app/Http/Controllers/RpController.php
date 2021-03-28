<?php

namespace App\Http\Controllers;

use App\Member;
use App\Role;
use App\RpReport;
use App\RpStats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RpController extends Controller{

    public function index(){
        if(Auth::guest() || !Auth::user()->member) abort(404);
        return view('evoque.rp.index', [
            'roles' => [
                'ets2' => Role::with([
                    'members' => function($query){
                        $query->where('visible', '1');
                    },
                    'members.role' => function($query){
                        $query->where('visible', '1');
                    },
                    'members.stat' => function($query){
                        $query->where('game', 'ets2')->whereNotNull('level');
                    }
                ])->where('visible', 1)->get()->groupBy('group')->filter(function($group){
                    foreach($group as $role){
                        foreach($role->members as $member){
                            $has = $member->stat && $member->topRole() == $role->id;
                            if($has) return true;
                        }
                    }
                    return false;
                }),
                'ats' => Role::with([
                    'members' => function($query){
                        $query->where('visible', '1');
                    },
                    'members.role' => function($query){
                        $query->where('visible', '1');
                    },
                    'members.stat' => function($query){
                        $query->where('game', 'ats')->whereNotNull('level');
                    }
                ])->where('visible', 1)->get()->groupBy('group')->filter(function($group){
                    foreach($group as $role){
                        foreach($role->members as $member){
                            $has = $member->stat && $member->topRole() == $role->id;
                            if($has) return true;
                        }
                    }
                    return false;
                })
            ]
        ]);
    }

    public function reports(){
        if(Auth::guest() || !Auth::user()->member) abort(404);
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
                'start-screen' => 'required|image|dimensions:max_width=3000,max_height=3000|max:5500',
                'finish-screen' => 'required|image|dimensions:max_width=3000,max_height=3000|max:5500',
                'new-id-screen' => 'nullable|image|dimensions:max_width=3000,max_height=3000|max:5500',
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
                'level' => 'required_with:accept|nullable|numeric',
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
                $stat->level = $request->input('level');
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
