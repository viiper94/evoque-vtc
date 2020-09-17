<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConvoysController extends Controller{

    public function index(){
        return view('evoque.convoys.index');
    }

}
