@extends('pc.layout.default')
@section('content')
    <nav-bar :data="{{json_encode($navs)}}" :categories="{{json_encode($goods_category_tree)}}" :showCategory="false"></nav-bar>
    <div class="container page-goods-category">
        <div class="left">
            @include('pc.goodsCategory.tree')
            <visited-goods-list></visited-goods-list>
            <goods-list-left-adv></goods-list-left-adv>
        </div>
        <div class="main">
            @include('pc.widgets.crumb')

            <div class="filters">
                <filter-params :params="{{json_encode($params)}}" url="{{url()->current()}}"></filter-params>
                @if(count($filters['brands'])>0)
                    <div class="filter">
                        <div class="name">
                            @lang('html.brand'):
                        </div>
                        <div class="values">
                            @foreach($filters['brands'] as $brand)
                                <span class="value">
                                    <a class="brand" href="javascript:void (0)" data-id="{{$brand->id}}" data-value="{{$brand->name}}">
                                        {{$brand->name}}
                                    </a>
                                </span>
                            @endforeach
                        </div>

                    </div>
                @endif
                @if(count($filters['attrs'])>0)
                    @foreach($filters['attrs'] as  $attr)
                        <div class="filter">
                          <div class="name">
                            {{$attr['name']}}:
                            </div>
                            <div class="values">
                                @foreach($attr['value'] as $value)
                                    <span class="value">
                                        <a class="attr" href="javascript:void (0)" data-name="{{$attr['name']}}" data-value="{{$value}}">
                                            {{$value}}
                                        </a>
                                    </span>
                                @endforeach
                            </div>

                        </div>
                    @endforeach


                @endif
            </div>

            <div class="goods-list cate-goods-list">
                <div class="sort-bar">
                    <a href="{{url()->current()}}?sort=synthesized" class="@if(request()->sort == 'synthesized') active @endif">@lang('html.goods_category.synthesized')<i class="iconfont icon-down"></i></a>
                    <a href="{{url()->current()}}?sort=sales" class="@if(request()->sort == 'sales') active @endif">@lang('html.goods_category.sales')<i class="iconfont icon-down"></i></a>
                    <a href="{{url()->current()}}?sort=price" class="@if(request()->sort == 'price') active @endif">@lang('html.goods_category.price')<i class="iconfont icon-down"></i></a>
                </div>
                <div class="list">
                    @if(count($list->items()) > 0)
                        @foreach($list->items() as $item)
                            <div class="item">
                                <a href="{{route('goods.show',['id'=>$item->id])}}">
                                    <div class="cover">
                                        <img src="{{$item->cover}}">
                                    </div>
                                    <div class="price">
                                        <span>@lang('html.goods.$'):{{$item->price}}</span>
                                    </div>
                                    <div class="name">
                                        <p>{{$item->name}}</p>
                                    </div>
                                </a>

                            </div>
                        @endforeach


                    @endif
                </div>
                <div class="pages">
                    {{ $list->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(function () {
            var url                     =   '{{url()->current()}}';
            var params                  =   JSON.parse('{!! json_encode($params) !!}');
            $('.brand').on('click',function () {
                params.brand            =   $(this).data('id')+':'+$(this).data('value');
                location.href           =   url+'?'+'sort='+params.sort+'&brand='+params.brand+'&attr='+(params.attr ? prams.attr : '')+'&category_id='+params.category_id;
            })
            
            $('.attr').on('click',function () {
                const that = $(this);
                var attr                =   params.attr;
                var flag                =   false;
                var  array           =   [];
                if(attr){

                   var attrs            =   attr.split(',');

                   for (var i=0;i<attrs.length;i++){
                       var a            =   attrs[i].split(':');
                       if(a[0]==that.data('name')){
                           a[1]         =   that.data('value');
                           flag         =   true;
                       }
                       array.push(a);
                   }


                }
                if(!flag){
                    array.push([that.data('name'),that.data('value')]);
                }
                var str                 =   '';
                var arr                 =   [];
                array.forEach(function(item){
                    arr.push(item[0]+':'+item[1]);
                });
                str                     =   arr.join(',');
                params.attr             =   str;
                location.href           =   url+'?'+'sort='+params.sort+'&brand='+params.brand+'&attr='+params.attr+'&category_id='+params.category_id;
            })
        })
    </script>
@endsection