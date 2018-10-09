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
    <div class="index-safe">
        <div class="container">
            <ul>
                <li class="item">
                    <p class="icon">监</p>
                    <p class="title">@lang('html.drug_control_certification')</p>
                    <p class="text">@lang('html.drug_control_certification_text')</p>
                </li>

                <li class="item">
                    <p class="icon">授</p>
                    <p class="title">@lang('html.brand_authorization')</p>
                    <p class="text">@lang('html.brand_authorization_text')</p>

                </li>

                <li class="item">
                    <p class="icon">隐</p>
                    <p class="title">@lang('html.privacy_packing')</p>
                    <p class="text">@lang('html.privacy_packing_text')</p>
                </li>
                <li class="item">
                    <p class="icon">正</p>
                    <p class="title">@lang('html.product_guarantee')</p>
                    <p class="text">@lang('html.product_guarantee_text')</p>
                </li>
            </ul>
        </div>
    </div>
    @include('pc.widgets.footer')
    <welcome></welcome>
@endsection