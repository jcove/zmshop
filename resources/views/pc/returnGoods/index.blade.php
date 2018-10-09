@extends('pc.user.base')
@section('main')
    <return-goods :orders="{{json_encode($list->items())}}" :total="{{$list->total()}}" :page-size="{{$list->perPage()}}"></return-goods>
@endsection