<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DoneController extends Controller
{
    public function index(Request $request){
        return view('frontend.done');
    }
}
