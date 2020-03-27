<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Skill;
use Auth;
use Illuminate\Support\Facades\Input;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $skill = Skill::where('title','=',$id)->first();
        return view('posts.create')->with('skill',$skill);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title'=>'required',
            'body'=>'required']);
        $skillid = Input::get('skill_id');
        $skill = Skill::where('id','=',$skillid)->first()->title;
        $post = new Post;
        $post->body = $request->input('body');
        $post->title = $request->input('title');
        $post->user_id = Auth::user()->id;
        $post->skill_id = $skillid;
        $post->hide = 0;
        $post->save();

        return redirect()->route('skills.show', ['id' => $skill])->with('success','Post Created');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);
        $skill = Skill::where('id','=',$post['skill_id'])->first();
        if(Auth::user()->id !== $post->user_id){
            return redirect()->back()->with('error','The post doesn\'t belong to you');
        }
        return view('posts.edit')->with(['post'=>$post, 'skill'=>$skill]);
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
        $this->validate($request, [
            'title'=>'required',
            'body'=>'required']);
        $post = Post::find($id);
        if(Auth::user()->id !== $post->user_id){
            return redirect()->back()->with('error','The post doesn\'t belong to you');
        }
        $post->body = $request->input('body');
        $post->title = $request->input('title');
        $post->save();

        return redirect()->back()->with('success','Post Updated');
    }

    //hide post
    public function hide($id)
    {
        $post = Post::find($id);
        if(Auth::user()->id !== $post->user_id){
            return redirect()->back()->with('error','The post doesn\'t belong to you');
        }
        $post->hide = 1;
        $post->save();

        return 'true';
    }

    //unhide post
    public function unhide($id)
    {
        $post = Post::find($id);
        if(Auth::user()->id !== $post->user_id){
            return redirect()->back()->with('error','The post doesn\'t belong to you');
        }
        $post->hide = 0;
        $post->save();

        return 'true';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        if(Auth::user()->id !== $post->user_id){
            return redirect()->back()->with('error','The post doesn\'t belong to you');
        }
        $post->delete();
        return 'true';
    }
}
