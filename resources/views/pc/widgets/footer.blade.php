<div class="footer ">
    <div class="container">
        <div class="shop-rules">
            @if(isset($footer_categories) )
            @foreach($footer_categories as $category)
                @if($loop->index < 4)
                    <div class="rule">
                        <h3 class="title">{{$category->name}}</h3>
                        @foreach($category->articles as $article)
                            <p class="article">
                                <a href="{{route('article.show',['id'=>$article->id])}}">
                                    {{$article->title}}
                                </a>

                            </p>
                        @endforeach
                    </div>
                @endif

            @endforeach
            @endif
        </div>
        <div class="shop-info">
            <div class="subscribe float-left">
                <h3 class="title">
                    @lang('html.subscribe_us')
                </h3>
                <div class="qr">
                    <img src="{{storage_url(config('shop.subcribe_qr'))}}">
                </div>
            </div>
            <div class="tel float-left">
                <h3 class="title ">
                    @lang('html.customer_service_phone')
                </h3>
                <p class="phone">
                    @lang('html.china_phone')
                </p>
                <p class="phone">
                    {{config('shop.customer_service_phone')}}
                </p>
                <p class="phone">
                    @lang('html.hk_phone')
                </p>
                <p class="phone">
                    {{config('shop.customer_service_hk_phone')}}
                </p>
            </div>
        </div>
    </div>

</div>
<div class="side-bar">
    <a href="javascript:void(0)" id="kefu" title="@lang('html.custom_service')">
        <i class="iconfont icon-kefu"></i>
    </a>
    <a href="javascript:scrollTop()" title="@lang('html.back_top')">
        <i class="iconfont icon-top"></i>
    </a>

</div>
