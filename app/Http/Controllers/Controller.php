<?php

namespace App\Http\Controllers;

use App\Member;
use App\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(){
        return view('index');
    }

    public function rulesNobodyRead(){
        return view('rules');
    }

    public function apply(){
        return view('apply');
    }

    public function convoys(){
        return view('convoys');
    }

    public function members(){
        return view('members', [
            'roles' => Role::with(['members', 'members.user', 'members.role'])->get()->groupBy('group')
        ]);
    }

}
