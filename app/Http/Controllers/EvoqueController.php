<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Member;
use App\Role;
use Illuminate\Http\Request;

class EvoqueController extends Controller{

    public function members(){
        return view('evoque.members');
    }

    public function profile(){
        return view('evoque.profile.index');
    }

}
