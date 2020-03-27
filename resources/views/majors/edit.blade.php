@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-body">
                    <h3>Edit Major</h3>
                    <hr>
                    <div class="col-md-12">
                        {!! Form::open(['action' => ['MajorsController@update',$major->id], 'method'=> 'POST']) !!}
                        <div class="form-group">
                            {{Form::label('major','Major')}}
                            {{Form::text('major',$major->title,['class'=>'form-control col-md-4', 'placeholder'=>'Major'])}}
                        </div>
                        {{Form::hidden('_method','PUT')}}
                        {{Form::submit('Submit',['class'=>'btn btn-primary'])}}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection