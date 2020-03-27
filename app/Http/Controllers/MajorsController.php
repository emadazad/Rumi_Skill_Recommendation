<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Major;
use Auth;

class MajorsController extends Controller
{
        /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $major = Major::where('title','=',$id)->first();
        if(Auth::user()->major->title !== $id){
            return redirect()->back()->with('error','This major doesn\'t belong to you');
        }
        return view('majors.edit')->with('major',$major);
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
        $this->validate($request,
            ['major'=>'required']);
        $decrement = Major::find($id)->decrement('count');
        $newmajor = $request->input('major');
        if(Major::where('title','=',$newmajor)->exists()){
            $majorid = Major::where('title','=',$newmajor)->first()->id;
            Major::where('title','=',$newmajor)->increment('count');
        }
        else{ 
            $major = new Major;
            $major->title = $newmajor;
            $major->count = 1;
            $major->save();
            $majorid = $major->id;
        }
        Auth::user()->update(['major_id' => $majorid]);

        return redirect('home')->with('success','Major Updated');
    }
}
