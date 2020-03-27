<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Skill;
use App\skills_users;
use App\User;
use App\Post;
use App\Major;
use App\Recommendation;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = user::all();
        $user = Auth::user();
        $skills = skill::orderBy('count','desc')->get();
        $userskills = skills_users::where('user_id','=',$user->id)->get();
        
        $rectype1 = Recommendation::where('user_id','=',$user->id)->where('type','=',"1")->where('expired','=',0)->orderBy('value', 'desc')->first();
        if ($rectype1 != Null) {
            $count1 = Skill::where('title','=',$rectype1->skill)->first()->count;
            $rectype1['count'] = $count1;
        }

        $rectype2 = Recommendation::where('user_id','=',$user->id)->where('type','=',"2")->where('expired','=',0)->orderBy('value', 'desc')->first();
        if ($rectype2 != Null) {
            $count2 = Skill::where('title','=',$rectype2->skill)->first()->count;
            $rectype2['count'] = $count2;
        }

        $rectype3 = Recommendation::where('user_id','=',$user->id)->where('type','=',"3")->where('expired','=',0)->orderBy('value', 'desc')->first();
        if ($rectype3 != Null) {
            $count3 = Skill::where('title','=',$rectype3->skill)->first()->count;
            $rectype3['count'] = $count3;
        }
        
        return view('home')->with(['userskills'=>$userskills,'skills'=>$skills,'user'=>$user,'major'=>$user->major,'userskill'=>$user->skills,'posts'=>$user->posts,'rectype1'=>$rectype1,'rectype2'=>$rectype2,'rectype3'=>$rectype3]);
    }

}