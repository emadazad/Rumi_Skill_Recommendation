@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-body">
                    <h3>Create Post
                        <span style="font-size: 15px"><a href="/skills/{{$skill->title}}" class="skill-tag" style="display: ">(#{{$skill->title}})</a></span></h3>
                    <hr>
                    <div class="col-md-12">
                        {!! Form::open(['action' => 'PostsController@store', 'method'=> 'POST']) !!}
                        <div class="form-group">
                            {{Form::label('title','Title')}}
                            {{Form::text('title','',['class'=>'form-control col-md-4', 'placeholder'=>'Title'])}}
                        </div>
                        <div class="form-group">
                            {{Form::label('body','Body')}}
                            {{Form::textarea('body','',['class'=>'form-control col-md-6', 'placeholder'=>'Body'])}}
                        </div>
                        {{Form::submit('Submit',['class'=>'btn btn-primary'])}}
                        {{ Form::hidden('skill_id', $skill->id) }}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection