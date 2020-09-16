<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApplicationsController extends Controller{

    public function index(){
        return view('evoque.applications.index');
    }

}
