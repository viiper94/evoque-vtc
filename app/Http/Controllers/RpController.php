<?php

namespace App\Http\Controllers;

use App\RpReport;
use App\RpStats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RpController extends Controller{

    public function index($game = 'ets2'){
        if(Auth::guest()) abort(404);
        return view('evoque.rp.index', [
            'stats' => RpStats::with(['member', 'member.user'])->get()
        ]);
    }

    public function report(Request $request){
        if(Auth::guest()) abort(404);
        if($request->post()){
            $this->validate($request, [
                'start-screen' => 'required|image|dimensions:max_width=3000,max_height=3000|max:5500',
                'finish-screen' => 'required|image|dimensions:max_width=3000,max_height=3000|max:5500',
                'new-id-screen' => 'nullable|image|dimensions:max_width=3000,max_height=3000|max:5500',
                'game' => 'required|string',
                'note' => 'nullable|string'
            ]);
            $report = new RpReport();
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
        return view('evoque.rp.report');
    }

}
