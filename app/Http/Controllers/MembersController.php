<?php

namespace App\Http\Controllers;

use App\Member;
use App\Role;
use Illuminate\Http\Request;

class MembersController extends Controller{

    public function index(){
        return view('evoque.members.index', [
            'roles' => Role::with(['members', 'members.user', 'members.role'])->get()->groupBy('group')
        ]);
    }

}
