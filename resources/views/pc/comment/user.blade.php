@extends('pc.user.base')
@section('main')
    <comment :comments="{{json_encode($list->items())}}" :per-page="{{$list->perPage()}}" :total="{{$list->total()}}"></comment>
@endsection