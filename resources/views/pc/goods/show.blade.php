@extends('pc.layout.default')
@section('content')
    <nav-bar :data="{{json_encode($navs)}}" :categories="{{json_encode($goods_category_tree)}}" :showCategory="false"></nav-bar>
    @include('pc.widgets.crumb')
    <div class="container">
        <div class="goods-base-info">
            <div class="base-info">
                <div class="goods-images">
                    <!-- 商品大图介绍 start [[-->
                    <div class="img">
                        <a class="jqzoom" href="{{$data->cover}}" rel="gal1" title="{{$data->name}}">
                            <img id="zoomimg" class="img-responsive" src="{{$data->cover}}" jqimg="{{{$data->cover}}}" title="{{$data->name}}">
                        </a>
                    </div>
                    <!-- 商品大图介绍 end ]]-->
                    <!-- 商品小图介绍 start [[-->
                    <div class="small-img">
                        <a class="left" href="javascript:void(0)">
                            <i class="iconfont icon-zuo"></i>
                        </a>
                        <a class="right" href="javascript:void(0)">
                            <i class="iconfont icon-right"></i>
                        </a>
                        <div class="small-pic">
                            <ul class="">
                                @foreach($data->galleries as $gallery)
                                    <li class="small-pic-li">
                                        <a href="javascript:;"
                                           rel="{gallery: 'gal1', smallimage: '{{$gallery->path}}',largeimage: '{{$gallery->path}}'}"
                                           class="@if($loop->first ) zoomThumbActive @endif">
                                            <img src="{{$gallery->path}}" data-img="{{$gallery->path}}"
                                                 data-big="{{$gallery->path}}">
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                    </div>
                    <!-- 商品小图介绍 end ]]-->
                    <div class="goods-tools">
                        <div class="goods-sn-box">
                            <div class="goods-sn float-left">
                                <span>@lang('html.goods_sn')</span>
                                <span>{{$data->goods_sn}}</span>
                            </div>
                            <a class="goods-collect float-right" href="javascript:collect()">
                                <i class="iconfont collect-icon icon-heart1"></i>
                                <span class="collect">@lang('html.operate.collect')</span>
                            </a>
                        </div>
                        <p class="tips">
                            <span>@lang('html.info.tips')</span>
                            @lang('html.text.goods_tips')
                        </p>
                    </div>
                </div>
                {{--基本信息--}}
               <goods-info :info="{{$data}}"></goods-info>

            </div>
            <div class="brief">
                <p class="title">
                    {{config('shop.site_title')}}
                </p>
                <div class="safe">
                    <ul>
                        <li class="item">
                            <p class="icon">正</p>
                            <p class="text">@lang('html.product_guarantee')</p>
                        </li>
                        <li class="item">
                            <p class="icon">授</p>
                            <p class="text">@lang('html.brand_authorization')</p>
                        </li>
                        <li class="item">
                            <p class="icon">监</p>
                            <p class="text">@lang('html.drug_control_certification')</p>
                        </li>
                        <li class="item">
                            <p class="icon">免</p>
                            <p class="text">@lang('html.save_money')</p>
                        </li>
                    </ul>
                </div>
                <div class="qr">
                    <img src="{{config('shop.mobile_qr')}}">
                </div>
            </div>
        </div>
        <div class="goods-detail">
            <div class="relation-info">
                <relation-category :category_id="{{$data->category_id}}"></relation-category>
                <relation-brand :category_id="{{$data->category_id}}"></relation-brand>
            </div>
            <div class="detail">
                <div class="tabs">
                    <ul>
                        <li class="active">
                            @lang('html.goods_brief')
                        </li>
                        <li class="">
                            @lang('html.instruction')
                        </li>
                        <li class="">
                            @lang('html.comments')
                        </li>
                    </ul>
                </div>
                <div class="description panel" style="display: block">
                    <relations :goods_id="{{$data->id}}"></relations>
                    {!! $data->content !!}
                </div>
                <div class="instruction panel">
                    {!! $data->instruction !!}
                </div>
                <div class="comments panel">
                    <goods-comments :goods_id="{{$data->id}}">
                    </goods-comments>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('script')
    <link rel="stylesheet" href="{{asset('css/jqzoom.css')}}">
    <script src="{{asset('js/jquery.jqzoom-min.js')}}"></script>
    <script>
        let collection = null;

        $(document).ready(function () {
            $(".jqzoom").jqzoom({
                zoomType: 'standard',
                lens: true,
                preloadImages: false,
                alwaysOn: false
            });
            var size = $('.small-pic-li').length;
            var li      =   $('.small-pic-li').eq(0);
            var liWidth = li.width()+parseInt(li.css('padding'))*2;

            var width = size*liWidth;
            var ul      =   $('.small-pic ul');
            ul.width(width);
            let max = width-420;
            $('.left').on('click',function () {
                if(parseInt(ul.css('left'))> -max){
                    ul.css('left',(parseInt(ul.css('left'))-liWidth)+'px');
                }
            })
            $('.right').on('click',function () {
                if(parseInt(ul.css('left'))<0){
                    ul.css('left',(parseInt(ul.css('left'))+liWidth)+'px');
                }
            })
            
            $('.tabs li').on('click',function () {
                let $target = $(this);
                let index                =   $(".tabs li").index($target);
                $(this).siblings().removeClass('active');
                $(this).addClass('active');
                $('.panel').hide();
                $('.panel').eq(index).show();
            })
            $.get("{{route('collection.check',['id'=>$data->id])}}",{},function (response) {
                if(response){
                    collection = response.data;
                    $('.collect-icon').removeClass('icon-heart1').addClass('icon-heart11').css('color','red');
                }
            })
        });
        let token = document.head.querySelector('meta[name="csrf-token"]');
        function collect() {
            const collectUrl = "{{route('collection.store')}}";
            if(collection){
                $.ajax({
                    type: 'POST',
                    url: collectUrl+'/'+collection.id,
                    data: {'_method':"delete",'csrf-token':token.content},
                    success: function (response) {
                        $('.collect-icon').removeClass('icon-heart11').addClass('icon-heart1').css('color','black');
                        collection = null
                    },
                    dataType: 'json',
                    headers : {'X-CSRF-TOKEN':token.content},
                });
            }else {
                $.ajax({
                    type: 'POST',
                    url: collectUrl,
                    data: {goods_id:"{{$data->id}}",'csrf-token':token.content},
                    success: function (response) {
                        $('.collect-icon').removeClass('icon-heart1').addClass('icon-heart11').css('color','red');
                        collection = response.data;
                    },
                    dataType: 'json',
                    headers : {'X-CSRF-TOKEN':token.content},
                });

            }

        }

        //历史记录
        var array = JSON.parse(localStorage.getItem('visited_goods_list')) ||  new Array();

        var flag = false;
        array.forEach(function (item) {
            if(item.id ==={{$data->id}}){
                flag = true
            }
        })
        if(!flag){
            const goods = {id:{{$data->id}},name:'{{$data->name}}',price: '{{$data->price}}',cover:'{{$data->cover}}'};
            array.push(goods);
            if(array.length > 20){
                array.splice(0,1)
            }
            localStorage.setItem('visited_goods_list',JSON.stringify(array))
        }

    </script>
@endsection