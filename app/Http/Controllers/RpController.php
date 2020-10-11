<?php

namespace App\Http\Controllers;

use App\Role;
use App\RpReport;
use App\RpStats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class RpController extends Controller{

    public function index($game = 'ets2'){
        if(Auth::guest()) abort(404);
        return view('evoque.rp.index', [
//            'stats' => RpStats::with(['member', 'member.user'])->where('game', $game)->get(),
            'roles' => Role::with(['members', 'members.role' => function($query){
                $query->where('visible', '1');
            }, 'members.stat' => function($query) use ($game){
                $query->where('game', $game);
            }])->where('visible', 1)->get()->groupBy('group'),
            'game' => $game
        ]);
    }

    public function reports(){
        if(Auth::guest()) abort(404);
        return view('evoque.rp.reports', [
            'reports' => RpReport::with('member')->orderBy('created_at', 'desc')->paginate(10)
        ]);
    }

    public function addReport(Request $request){
        if(Auth::guest()) abort(404);
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
        return view('evoque.rp.edit', [
            'report' => $report
        ]);
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
            $stat = RpStats::where('member_id', $report->member_id)->first();
            if(is_null($stat)){
                $stat = new RpStats();
                $stat->member_id = $report->member_id;
            }
            $stat->distance += $request->input('distance');
            $stat->weight += $request->input('weight');
            $stat->bonus += $request->input('bonus');
            $stat->level = $request->input('level');
            $stat->quantity += 1;
            $report->status = true;
            if($stat->save() && $report->save()) return redirect()->route('evoque.rp')->with(['success' => 'Отчёт принят!']);
            return redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('evoque.rp.accept', [
            'report' => $report
        ]);
    }

}
