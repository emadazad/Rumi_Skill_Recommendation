<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function welcome(){
        return view('welcome');
    }
    public function about(){
        $aaa = 'About us';
        return view('pages.about')->with(['title'=>$aaa]);
    }
}