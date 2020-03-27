<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\skill;
use App\user;
use Illuminate\Support\Facades\Auth;
use App\skills_users;
use PHPUnit\Framework\Constraint\IsTrue;
use Illuminate\Support\Facades\Input;

class SkillsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $skills = skill::orderby('title','asc')->get();
        return view ('skills.skills')->with(['skills'=> $skills]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = Auth::user()->id;
        if(input::has('skill_id')){
            $skilluser = new skills_users;
            $skilluser->user_id = $user_id;
            $skilluser->skill_id = $request->input('skill_id');
            Skill::where('id','=',$request->input('skill_id'))->increment('count');
            $skilluser->save();
            return redirect('home')->with('success', 'Skill Added');
        }
        if(input::has('title')) {
            $this->validate($request, ['title' => 'required']);
            $skill = Skill::where('title', '=', $request->input('title'))->first();
            if ($skill !== null) {
                $skillid = $skill->id;
                if (skills_users::where('skill_id', '=', $skillid)->where('user_id', '=', $user_id)->exists()) {
                    return redirect('home')->with('success', 'You Already Have This Skill!');
                } 
                else {
                    $skilluser = new skills_users;
                    $skilluser->user_id = $user_id;
                    $skilluser->skill_id = $skillid;
                    $skill->increment('count');
                    $skilluser->save();
                    return redirect('home')->with('success', 'Skill Added');
                }
            } else {
                $skill = new Skill;
                $skill->title = $request->input('title');
                $skill->count = 1;
                $skill->save();
                $skilluser2 = new skills_users;
                $skilluser2->user_id = $user_id;
                $skilluser2->skill_id = $skill->id;
                $skilluser2->save();
                return redirect('home')->with('success', 'Skill Added');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user_id = Auth::user()->id;
        // return $user_id;
        $skill = Skill::where('title','=',$id)->first();
        $skill_id = strval($skill->id);
        $posts = \DB::connection('mongodb')->collection('posts')->where('skill_id','=',$skill_id)->where(function ($query) use ($user_id) {
                $query->where('hide', '=', 0)
                      ->orWhere('user_id', '=', $user_id);
            })->orderBy('updated_at', 'desc')->get();
        return view('skills.skill')->with(['posts'=>$posts,'skill'=>$skill]);
    }
    
    //returning user posts
    public function showMyPosts($id)
    {
        $user_id = Auth::user()->id;
        $posts = \DB::connection('mongodb')->collection('posts')
        ->where('user_id','=',$user_id)->where('skill_id','=',$id)->orderBy('updated_at', 'desc')->get();
        return $posts;
    }

    //returnin searched skill
    public function searchSkills()
    {
        $keyword = Input::get('keyword');
        if ($keyword == Null){
            $search = Skill::all();
            return $search;
        }
        $search = Skill::where('title','like', '%' . $keyword . '%')->orderBy('count','desc')->get();
        return $search;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $skill = skills_users::find($id);
        Skill::where('id','=',$skill->skill_id)->decrement('count');
        $skill->delete();
        return redirect('home')->with('success', 'Skill Removed');
    }
    public function destroy2(Request $request)
    {
        $skill = skill::where('title','=',$request->input('userskill'))->first();
        $skill->decrement('count');
        $skillid = $skill->id;
        $usid = skills_users::where('skill_id','=',$skillid)->where('user_id','=',Auth::user()->id)->first()->id;
        $userskill = skills_users::find($usid);
        $userskill->delete();
        return redirect('home')->with('success', 'Skill Removed');
    }
}
