<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConstructionController extends Controller
{
    public function index (){
        return view('construction-operation');
    }
}
