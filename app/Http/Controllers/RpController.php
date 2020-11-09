<?php

namespace App\Http\Controllers;

use App\Role;
use App\RpReport;
use App\RpStats;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class RpController extends Controller{

    public function index($game = 'ets2'){
        if(Auth::guest()) abort(404);
        return view('evoque.rp.index', [
            'roles' => Role::with([
                'members' => function($query){
                                $query->where('visible', '1');
                            },
                'members.role' => function($query){
                                $query->where('visible', '1');
                            },
                'members.stat' => function($query) use ($game){
                                $query->where('game', $game)->whereNotNull('level');
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
            'game' => $game
        ]);
    }

    public function reports(){
        if(Auth::guest()) abort(404);
        $reports = RpReport::with('member')->whereDate('created_at', '>=', Carbon::today()->subWeeks(1));
        if(Gate::denies('manage_rp')) $reports->where('member_id', Auth::user()->member->id);
        return view('evoque.rp.reports', [
            'reports' => $reports->orderBy('created_at', 'desc')->paginate(10)
        ]);
    }

    public function addReport(Request $request){
        if(Auth::guest()) abort(404);
        if(Gate::denies('do_rp')) abort(403);
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
            'report' => $report
        ]);
    }

    public function deleteReport(Request $request, $id){
        $report = RpReport::findOrFail($id);
        if(Gate::denies('admin') && (Gate::denies('do_rp') || $report->member_id != Auth::user()->member->id)) abort(403);
        $report->deleteImages(public_path('/images/rp/'));
        return $report->delete() ?
            redirect()->route('evoque.rp.reports')->with(['success' => 'Отчёт успешно удалён!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function acceptReport(Request $request, $id){
        if(Gate::denies('manage_rp')) abort(403);
        $report = RpReport::with('member')->where('id', $id)->first();
        if($request->post()){
            $this->validate($request, [
                'distance' => 'required|numeric',
                'weight' => 'required|numeric',
                'level' => 'required|numeric',
            ]);
            $stat = RpStats::where(['member_id' => $report->member_id, 'game' => $report->game])->first();
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
            $report->status = true;
            if($stat->save() && $report->save()) return redirect()->route('evoque.rp', $report->game)->with(['success' => 'Отчёт принят!']);
            return redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('evoque.rp.accept', [
            'report' => $report
        ]);
    }

    public function editStat(Request $request, $id){
        if(Gate::denies('manage_rp')) abort(403);
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

    public function createResults($game){
        if(Gate::denies('manage_rp')) abort(403);
        $stats = RpStats::where([['game', '=', $game], ['quantity', '>', '0']])->get();
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
