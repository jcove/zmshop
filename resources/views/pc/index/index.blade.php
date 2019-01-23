@extends('pc.layout.base')
@section('body')

    @include('pc.widgets.header')
   <banner position="pc_index_header_banner">

   </banner>
    @include('pc.widgets.search_bar')

    @include('pc.widgets.nav')
    <slider :data="{{$banners}}"></slider>
    @include('pc.widgets.recommend_category')
    @foreach($categories as $category)
        <category-box :index="{{$loop->index}}"
            :category="{{$category}}">
        </category-box>
    @endforeach
    <hot-goods-list></hot-goods-list>
    @include('pc.widgets.footer')
@endsection