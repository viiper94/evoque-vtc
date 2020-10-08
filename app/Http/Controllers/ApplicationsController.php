<?php

namespace App\Http\Controllers;

use App\Application;
use App\Http\Controllers\Controller;
use App\Recruitment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ApplicationsController extends Controller{

    public function index(){
        if(Gate::denies('manage_members')) abort(403);
        $recruitment = Recruitment::all();
        return view('evoque.applications.index', [
            'applications' => $recruitment->sortBy('created_at')->sortBy('status')
        ]);
    }

    public function acceptRecruitment(Request $request, $id){
        if(Gate::denies('manage_members')) abort(403);
        $application = Recruitment::findOrFail($id);
        $application->status = 1;
        return $application->save() ?
            redirect()->back()->with(['success' => 'Зявка принята! Для завершения процесса добавления сотрудника на сайт, ему надо вступить в ВТК на сайте TruckersMP.']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);;
    }

    public function deleteRecruitment(Request $request, $id){
        if(Gate::denies('manage_members')) abort(403);
        $application = Recruitment::findOrFail($id);
        return $application->delete() ?
            redirect()->back()->with(['success' => 'Зявка удалена!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);;
    }

    // TODO Applications for members (vacation, plate change, firing)

}
