@extends('pc.user.base')
@section('main')
    <order-list :orders="{{json_encode($list->items())}}" :total="{{$list->total()}}" :pages="{{$list->lastPage()}}"></order-list>
@endsection