@extends('pc.layout.no_header')
@section('content')
    <div class="container user-forget-section">
        @include('pc.user.user_header')
    </div>
    <forget-form t="{{ csrf_token()}}" redirect_url="{{url()->previous()}}">

    </forget-form>
@endsection