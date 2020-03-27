@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div style="border-radius: 30px;" class="card">
                    <div class="card-body">
                        <div style= "text-align: center; margin-top: 30px;margin-bottom:30px">
                            <h1 style="color:#000033;font-size:80px">WELCOME</h1>
                            <br>
                            <p style="color:darkslategrey;font-size: 25px">Hey, nice to have you on EN, Emad Networking!</p>
                            <p style="margin-top:-12px;color:darkslategrey;font-size: 14px">Go ahead and login if you have an account, otherwise feel free to register</p>
                            <a style="font-size:20px; border-radius:15px;width: 130px" class="btn btn-success" href="/login">Login</a>
                            <a style="margin-left: 30px; font-size:20px; border-radius:15px;width: 130px" class="btn btn-primary" href="/register">Register</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
