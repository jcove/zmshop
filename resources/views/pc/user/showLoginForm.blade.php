@extends('pc.layout.no_header')
@section('content')
    <div class="container user-login-section">
        @include('pc.user.user_header')

    </div>
    <div class="user-login" style="overflow: hidden">
        <div class="container">
            <login-form redirect_url="{{$redirect_url}}" style="float: left">

            </login-form>
            <div class="right float-right">
                <img src="{{asset('images/login_right_bg.png')}}">
            </div>
        </div>
    </div>

@endsection