@extends('pc.layout.default')
@section('content')
    <nav-bar :data="{{json_encode($navs)}}" :categories="{{json_encode($goods_category_tree)}}" :showCategory="false"></nav-bar>
    <div class="container user-main">
        @include('pc.user.menu')
        @include('pc.user.main')
    </div>
@endsection