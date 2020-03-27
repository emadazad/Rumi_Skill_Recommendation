@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-body">
                        <a href="/restart" style="color: white;" class="btn btn-danger float-right">Restart</a>
                        @if(isset($quotes))
                            <h3>Click on the quote that you like more!</h3>
                            <div class="card-body">
                                <div class="">
                                    {!! Form::open(['action' => ['QuotesController@update',$quotes[0]->_id, $quotes[1]->_id], 'method'=> 'POST']) !!}
                                    {{Form::hidden('_method','POST')}}
                                    {{Form::submit('" '.$quotes[0]->quote.' "',['class'=>'quote-container'])}}
                                    {!! Form::close() !!}
                                </div>
                                <div class="">
                                    {!! Form::open(['action' => ['QuotesController@update',$quotes[1]->_id,$quotes[0]->_id], 'method'=> 'POST']) !!}
                                    {{Form::hidden('_method','POST')}}
                                    {{Form::submit('" '.$quotes[1]->quote.' "',['class'=>'quote-container'])}}
                                    {!! Form::close() !!}
                                </div>
                        @else
                            <h1>Top 3 Quotes:</h1>
                            <div style="padding: 15px">
                                @foreach($bests as $best)
                                    <p style="font-size: 20px">
                                        {{$loop->iteration}}-
                                        {{$best->quote}}
                                    <p>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection