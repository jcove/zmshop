@extends('pc.layout.no_header')
@section('content')
    <div class="container user-register-section">
        @include('pc.user.user_header')
    </div>
    <register-form t="{{ csrf_token()}}" redirect_url="{{url()->previous()}}">

    </register-form>
@endsection