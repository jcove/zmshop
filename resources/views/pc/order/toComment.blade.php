@extends('pc.layout.default')
@section('content')
    <div class="container">
        <comment-form :id="{{$id}}"></comment-form>
    </div>
@endsection