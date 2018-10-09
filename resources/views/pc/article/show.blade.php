@extends('pc.layout.default')
@section('content')
    <div class="article-info container">
        <div class="title-box">
            <h2 class="title">{{$data->title}}</h2>
        </div>
        <div class="content">
            {!! $data->content !!}
        </div>
    </div>
@endsection