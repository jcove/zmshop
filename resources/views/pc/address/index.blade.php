@extends('pc.user.base')
@section('main')
    <user-address :list="{{json_encode($list->all())}}">

    </user-address>
@endsection