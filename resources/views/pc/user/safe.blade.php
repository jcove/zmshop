@extends('pc.user.base')
@section('main')
    <safe-info :user="{{Auth::user()}}"></safe-info>
@endsection