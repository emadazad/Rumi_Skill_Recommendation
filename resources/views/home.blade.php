@extends('layouts.app')

@section('content')

<!-- javaScript -->
<script>
    function removePost(elt){
        try{
            var id= elt.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.id;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            $.ajax({
                    url: '/posts/'+id,
                    type: 'DELETE',
                    success: function(result) {
                        // Do something with the result
                        $("#"+id ).fadeOut('slow', function() { $(this).remove(); });
                        console.log('success');
                        },
                    error: function(e) {
                        $("#"+id ).fadeOut('slow', function() { $(this).remove(); });
                        console.log('error');
                        }
                    });
        }
        catch (err) {
            console.log(err.message);
        }
    }
    function hidePost(elt){
        var id= elt.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.id;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        $.post("/hide/"+id, function(){
            $(".hide-post-"+id).fadeOut(1,function() { $(this).remove(); });
            $(".tool-bar-"+id).prepend('<i onclick="unhidePost(this)" class="fas fa-lock padding-2 unhide-post-'+id+'"></i>');
            });
    }
    function unhidePost(elt){
        var id= elt.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.id;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        $.post("/unhide/"+id, function(){
            $(".unhide-post-"+id).fadeOut(1,function() { $(this).remove(); });
            $(".tool-bar-"+id).prepend('<i onclick="hidePost(this)" class="fas fa-lock-open padding-2 hide-post-'+id+'"></i>');
            });
    }
    function feedback(elt){
        var type = elt.parentNode.parentNode.parentNode.id.substring(5);
        var id= elt.parentNode.parentNode.id;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        $.post("/expireRec/"+id, function(value){
            console.log(value);
            $.post("/newRec/"+type, function(data){
                //alert(data);
                console.log(data);
                $("#"+id).fadeOut('slow',function() { 
                    $(this).remove(); 
                    if(data.length != 0){
                        var test = '<div id="'+data._id+'">';
                        test += '<div style="padding: 5px" class="float-right">';
                        test += '<i onclick="feedback(this)" class="fa fa-close"></i></div>';
                        test += '<div style="padding: 10px 5px 0px 5px">';
                        test += '<a style="font-size: 18px" href="/skills/'+data.skill+'" class="skill-tag"><b>#'+data.skill+'</b></a></div>';
                        test += '<div style="padding: 0 0 0 5px">';
                        test += '<img style="width:24px" src="/storage/17-512.jpg">';
                        test += '<span style="color:#5CA9FF; font-size: 14px ">'+data.count+'</span></div>';
                        test += '<div style="padding: 10px">';
                        test += '<button onclick="addskill(this)" class="add-skill">Add skill</button>';
                        test += '</div></div>';
                        //$("#"+id).fadeOut('slow',function() { $(this).remove(); });
                        $("#type-"+type).prepend(test);
                    }
                    else{
                        var test4 = '<div style="text-align: center; padding: 10px"><img style="text-align: center; width:90px" src="storage/Smiley.png"></div>';
                        $("#type-"+type).append(test4);
                    }
                });
            });
        });
    }
    function addskill(elt){
        var id = elt.parentNode.parentNode.id;
        var type = elt.parentNode.parentNode.parentNode.id.substring(5);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        $.post("/addskill/"+id, function(value){
            if( value != 'false'){
                console.log('skill added');
                var test2 = '<a href="/skills/'+value.skill+'" class="skill-tag">#'+value.skill+'</a>';
                test2 += '<form style="display: inline" method="POST" action="{{action("SkillsController@destroy2")}}">';
                test2 += '<input type="hidden" name="userskill" value="'+value.skill+'">'
                test2 += '<input type="hidden" name="_method" value="DELETE">';
                test2 += '{!! csrf_field() !!}';
                test2 += '<button style="display: inline; border: none; background: none "><i style="color: dimgrey" class="fa fa-close"></i></button></form>';
                test2 += '<img style="margin-left: -2px;width:24px" src="/storage/17-512.jpg">';
                test2 += '<font style="color:#5CA9FF; font-size: 12px; margin-right: 10px ">'+value.count+'</font>';
                $(".skill-set").append(test2);
            }
            else{
                console.log('You already have this skill!');
            }
            $.post("/expireRec/"+id, function(text){
                console.log(text);
                $("#"+id).fadeOut('slow',function() { 
                    $(this).remove();
                    $.post("/newRec/"+type, function(data){
                        console.log(data);
                        if(data.length != 0){
                            var test = '<div id="'+data._id+'">';
                            test += '<div style="padding: 5px" class="float-right">';
                            test += '<i onclick="feedback(this)" class="fa fa-close"></i></div>';
                            test += '<div style="padding: 10px 5px 0px 5px">';
                            test += '<a style="font-size: 18px" href="/skills/'+data.skill+'" class="skill-tag"><b>#'+data.skill+'</b></a></div>';
                            test += '<div style="padding: 0 0 0 5px">';
                            test += '<img style="width:24px" src="/storage/17-512.jpg">';
                            test += '<span style="color:#5CA9FF; font-size: 14px ">'+data.count+'</span></div>';
                            test += '<div style="padding: 10px">';
                            test += '<button onclick="addskill(this)" class="add-skill">Add skill</button>';
                            test += '</div></div>';
                            $("#type-"+type).append(test);
                        }
                        else{
                            var test3 = '<div style="text-align: center; padding: 10px"><img style="text-align: center; width:90px" src="storage/Smiley.png"></div>';
                            $("#type-"+type).append(test3);
                        }
                    });
                });
            });
        });
    }
    
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<!-- Rumi container -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3" style="text-align: center;">
                                @if(count($skills) == count($userskills))
                                <img src="/storage/HappyRumi.jpg">
                                @elseif(count($userskills)> 0)
                                    <img style="height: 130px; width: 130px" src="/storage/Picture1.jpg">
                                @elseif(count($userskills) == 0)
                                    <img src="/storage/SadRumi.jpg">
                                @endif
                            <h2 style="margin-top: 5px">Rumi</h2>
                            <p style="margin-top: -5px">Personal Assistant</p>
                            <a href="/rec" class="btn btn-light">try me!</a>
                        </div>
                        <div id="rec-body" class="col-md-8">
                            <br>
                            @if(count($skills) == count($userskills))
                                <h3>You have all the skill set. <h1 style="margin-top:34px">Try Rumi Jokes :-)</h1></h3>
                            @elseif(count($skills) - count($userskills)== 1)
                                <h3>Hey, you've got only one to go! You can do it!</h3>
                            @elseif(count($userskills) == 0)
                                <h3>Hi {{$user->name}}, it looks like you have not added any skills, how about adding these skills to your skill set and make me glad?</h3>
                            @elseif(count($userskills)> 0)
                                <h3>Hey, how about these skills?</h3>
                            @endif
                            <br>
                            <div class="row">
                                <div id="type-1" class="rec-container">
                                    @if(count($rectype1) != 0)
                                    <div id="{{isset($rectype1->_id)? $rectype1->_id : ''}}">
                                        <div style="padding: 5px" class="float-right">
                                            <i onclick="feedback(this)" class="fa fa-close"></i>
                                        </div>
                                        <div style="padding: 10px 5px 0px 5px">
                                            <a style="font-size: 18px" href="/skills/{{isset($rectype1->skill) ? $rectype1->skill : ''}}" class="skill-tag"><b>#{{isset($rectype1->skill) ? $rectype1->skill : ''}}</b></a>
                                        </div>
                                        <div style="padding: 0 0 0 5px">
                                            <img style="width:24px" src="/storage/17-512.jpg">
                                            <span style="color:#5CA9FF; font-size: 14px ">{{isset($rectype1->count) ? $rectype1->count : ''}}</span>
                                        </div>
                                        <div style="padding: 10px">
                                            <button onclick="addskill(this)" class="add-skill">Add skill</button>
                                        </div>
                                    </div>
                                    @else
                                    <div style="text-align: center; padding: 10px">
                                        <img style="text-align: center; width:90px" src="storage/Smiley.png">
                                    </div>
                                    <h3 style="text-align: center; font-size: 10px">"Skill Related"</h3>
                                    @endif
                                </div>
                                <div id="type-2" class="rec-container">
                                    @if(count($rectype2) != 0)
                                    <div id="{{isset($rectype2->_id)? $rectype2->_id : ''}}">
                                        <div style="padding: 5px" class="float-right">
                                            <i onclick="feedback(this)" class="fa fa-close"></i>
                                        </div>
                                        <div style="padding: 10px 5px 0px 5px">
                                            <a style="font-size: 18px" href="/skills/{{isset($rectype2->skill) ? $rectype2->skill : ''}}" class="skill-tag"><b>#{{isset($rectype2->skill) ? $rectype2->skill : ''}}</b></a>
                                        </div>
                                        <div style="padding: 0 0 0 5px">
                                            <img style="width:24px" src="/storage/17-512.jpg">
                                            <span style="color:#5CA9FF; font-size: 14px ">{{isset($rectype2->count) ? $rectype2->count : ''}}</span>
                                        </div>
                                        <div style="padding: 10px">
                                            <button onclick="addskill(this)" class="add-skill">Add skill</button>
                                        </div>
                                    </div>
                                    @else
                                    <div style="text-align: center; padding: 10px">
                                        <img style="text-align: center; width:90px" src="storage/Smiley.png">
                                    </div>
                                    <h3 style="text-align: center; font-size: 10px">"Major Related"</h3>
                                    @endif
                                </div>
                                <div id="type-3" class="rec-container">
                                    @if(count($rectype3) != 0)
                                    <div id="{{isset($rectype3->_id)? $rectype3->_id : ''}}">
                                        <div style="padding: 5px" class="float-right">
                                            <i onclick="feedback(this)" class="fa fa-close"></i>
                                        </div>
                                        <div style="padding: 10px 5px 0px 5px">
                                            <a style="font-size: 18px" href="/skills/{{isset($rectype3->skill) ? $rectype3->skill : ''}}" class="skill-tag"><b>#{{isset($rectype3->skill) ? $rectype3->skill : ''}}</b></a>
                                        </div>
                                        <div style="padding: 0 0 0 5px">
                                            <img style="width:24px" src="/storage/17-512.jpg">
                                            <span style="color:#5CA9FF; font-size: 14px ">{{isset($rectype3->count) ? $rectype3->count : ''}}</span>
                                        </div>
                                        <div style="padding: 10px">
                                            <button onclick="addskill(this)" class="add-skill">Add skill</button>
                                        </div>
                                    </div>
                                    @else
                                    <div style="text-align: center; padding: 10px">
                                        <img style="text-align: center; width:90px" src="storage/Smiley.png">
                                    </div>
                                    <h3 style="text-align: center; font-size: 10px">"Random"</h3>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<!-- Skill set container -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <h3>Skill, Experiences, and Competencies</h3>
                    <hr>
                    {!! Form::open(['action' => 'SkillsController@store', 'method'=> 'POST']) !!}
                        <div class="form-group">
                            {{Form::label('title','Add skill, experience, or competency tags:')}}
                            <br>
                            {{Form::text('title','',['class'=>'form-control col-md-6', 'placeholder'=>'e.g. Programming or Microsoft Office'])}}
                        </div>
                    {!! Form::close() !!}
                    <div class="skill-set">
                        @foreach($user->skills as $skill)
                            <a href="/skills/{{$skill->title}}" class="skill-tag">#{{$skill->title}}</a>
                            <form style="display: inline" method="POST" action="{{action('SkillsController@destroy',[$userskills->where('skill_id','=',$skill->id)->first()->id])}}">
                                <input type="hidden" name="_method" value="DELETE">
                                {!! csrf_field() !!}
                                <button style="display: inline; border: none; background: none "><i style="color: dimgrey" class="fa fa-close"></i></button>
                            </form>
                            <img style="margin-left: -2px;width:24px" src="/storage/17-512.jpg">
                            <font style="color:#5CA9FF; font-size: 12px; margin-right: 10px ">{{$skill->count}}</font>
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<!-- About container -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-body">
                    <h3>About {{$user->name}}</h3>
                    <hr>
                    <div class="col-md-4">
                        <p style="font-size: 20px; color:dimgrey"><b>Major:</b>
                            <font style="color:dimgrey;font-size: 18px">{{$major->title}}</font>
                            <a href="majors/{{$major->title}}/edit" class="btn btn-secondary float-right">Edit</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<!-- posts container -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-body">
                    <h3>Posts</h3>
                    <hr>
                    @if(count($posts) == 0)
                        <div class="row justify-content-center">
                            <div>You have no posts!</div>
                        </div>
                    @endif
                    @foreach($posts as $post)
                        <div class="container" id="{{$post['_id']}}">
                                <div class="row justify-content-center">
                                    <div class="col-md-8">
                                        <div class="card">
                                            <div class="card-header">
                                                {{$post['title']}}
                                                <div class="tool-bar-{{$post['_id']}} float-right">
                                                    @if($post['hide']==1)
                                                        <i onclick="unhidePost(this)" class="fas fa-lock padding-2 unhide-post-{{$post['_id']}}"></i>
                                                    @endif
                                                    @if($post['hide']==0)
                                                        <i onclick="hidePost(this)" class="fas fa-lock-open padding-2 hide-post-{{$post['_id']}}"></i>
                                                    @endif
                                                    <a href="/posts/{{$post['_id']}}/edit" class="fas fa-edit padding-2"></a>
                                                    <i onclick="removePost(this)" class="fas fa-trash-alt"></i>
                                                </div>
                                                <p><a style="margin: 0;" href="/skills/{{$post->skill->title}}" class="skill-tag float-right">(#{{$post->skill->title}})</a></p>
                                            </div>
                                            <div class="card-body">
                                                <p>{{$post['body']}}</p>
                                            </div>
                                        </div>
                                        <br>
                                    </div>
                                </div>
                            </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
