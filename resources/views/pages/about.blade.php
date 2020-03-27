@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-body">
                        <h3>{{$title}}</h3>
                        <hr>
                        <div class="col-md-12">
                            <p>On this application, the focus is to 
                            have Rumi recommending skills to users.</p>
                            <p>The recommendations are based on 
                            three different algorithms:</p>
                            <ul>
                                <li>Random</li>
                                <li>Major related</li>
                                <li>Skill related</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection