<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Recommendation;
use App\Skill;
use App\skills_users;
use App\Major;
use App\User;
use Auth;

class RecommendationsController extends Controller
{
    //Recommendation engine
    public function rec(){
    	$user = Auth::user();
    	$recommendations = Recommendation::where('user_id','=',$user->id)
    	->forceDelete();
        $SkillArray = array();
        $MajorArray = array();
        $RandomArray = array();
        $userskills = $user->skills;
        $allSkills = Skill::all();
        foreach ($allSkills as $oneSkill) {
        	$RandomArray[$oneSkill->title] = $oneSkill->count;
        }
        foreach ($user->major->users as $major_related_user) {
        	if($major_related_user->id == $user->id){
        			continue;
        		}
        	foreach ($major_related_user->skills as $major_otherskill) {
        			array_push($MajorArray, $major_otherskill->title);
        		}
        }
        foreach ($userskills as $userskill) {
        	foreach ($userskill->users as $skill_related_user) {
        		if($skill_related_user->id == $user->id){
        			continue;
        		}
        		foreach ($skill_related_user->skills as $skill_otherskill) {
        			array_push($SkillArray, $skill_otherskill->title);
        		}
	        }
	    }
	    $SkillArray2 = array_count_values($SkillArray);
	    $MajorArray2 = array_count_values($MajorArray);
	    foreach ($userskills as $userskill){
	    	unset($SkillArray2[$userskill->title]);
	    	unset($MajorArray2[$userskill->title]);
	    	unset($RandomArray[$userskill->title]);
	    }

	    foreach ($SkillArray2 as $key => $value) {
	    	$rec = new Recommendation;
        	$rec->user_id = $user->id;
        	$rec->skill = $key;
        	$rec->value = $value;
        	$rec->type = "1";
        	$rec->expired = 0;
        	$rec->save();
	    }
	    foreach ($MajorArray2 as $key2 => $value2) {
	    	$rec = new Recommendation;
        	$rec->user_id = $user->id;
        	$rec->skill = $key2;
        	$rec->value = $value2;
        	$rec->type = "2";
        	$rec->expired = 0;
        	$rec->save();
	    }
	    foreach ($RandomArray as $key3 => $value3) {
	    	$rec = new Recommendation;
        	$rec->user_id = $user->id;
        	$rec->skill = $key3;
        	$rec->value = $value3;
        	$rec->type = "3";
        	$rec->expired = 0;
        	$rec->save();
	    }

	    //print_r($SkillArray);
	    //print_r($SkillArray2);
        //return $skill->users;
        //return view('rec');
        return redirect('home')->with('success','Recommendations are all set');
    }
    public function new_type($type){
        $new = Recommendation::where('user_id','=',Auth::user()->id)->where('type','=',$type)->where('expired','=',0)->orderBy('value', 'desc')->first();
        if ($new != Null) {
            $count = Skill::where('title','=',$new->skill)->first()->count;
            $new['count'] = $count;
        }
        return $new;
    }
    public function expire($id){
        $rec = Recommendation::find($id);
        $rec->expired = 1;
        $rec->save();
        return "expired";
    }
    public function addskill($id){
        $rec = Recommendation::find($id);
        $user_id = Auth::user()->id;
        $skill = Skill::where('title','=',$rec->skill)->first();
        $skillid = $skill->id;
        if (skills_users::where('skill_id', '=', $skillid)->where('user_id', '=', $user_id)->exists()) {
                    return 'false';
                } 
        else {
            $skilluser = new skills_users;
            $skilluser->user_id = $user_id;
            $skilluser->skill_id = $skillid;
            $skill->increment('count');
            $skilluser->save();
        }
        $rec['count'] = $skill->count;
        return $rec;
    }
}
