@extends('pc.layout.default')
@section('content')
    <div class="container page-brand">
        <div class="categories">
            <brand-category :brand="{{$data}}"></brand-category>
        </div>
        <div class="brand ">
            <p class="name">{{$data->name}}</p>
            <div class="description ue">
                {!! $data->description !!}
            </div>
            <div class="goods-list">
                <goods-list :filter="{brand:'{{$data->id}}:{{$data->name}}'}"></goods-list>
            </div>
        </div>

    </div>
@endsection