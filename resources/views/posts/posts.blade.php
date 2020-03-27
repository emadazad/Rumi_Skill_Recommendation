@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-body">
                    <h3>All Posts</h3>
                    <hr>
                    <div class="col-md-12">
                        @foreach($posts as $post)
                            <h3>{{$post['title']}}</h3>
                            <p>{{$post['body']}}</p>
                            @if(Auth::user()->id == $post['user_id'])
                                <a href="/posts/{{$post['_id']}}/edit" class="btn btn-secondary">Edit</a>
                                {!! Form::open(['action' => ['PostsController@destroy',$post['_id']], 'method'=> 'POST', 'class' =>"float-right"]) !!}
                                {{Form::hidden('_method','DELETE')}}
                                {{Form::submit('Delete',['class'=>'btn btn-danger'])}}
                                {!! Form::close() !!}
                                <br>
                                <br>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection