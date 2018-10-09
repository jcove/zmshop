@extends('pc.user.base')
@section('main')
    <order-list :orders="{{json_encode($list->items())}}" :total="{{$list->total()}}" :page-size="{{$list->perPage()}}"></order-list>
@endsection