<div class="container search-bar">
    <div class="logo-box float-left" >
        <a href="{{url('/')}}">
            <img src="{{storage_url(config('shop.logo_path'))}}">
        </a>

    </div>
    <div class="search-adp float-left">
        {!! adp('search_bar_left') !!}
    </div>
    <div class="search-box float-left" id="search-bar">
        <div class="input">
            <input value="{{request()->input('q','')}}" placeholder="@lang('html.common.please_input_name')" id="keywords" type="text">
            <span class="input-suffix">
                 <button  class="btn search-btn">@lang('html.search')</button>
            </span>

        </div>
        <div class="keywords">
            @foreach(explode(',',config('shop.search_keywords','')) as $item)
                <span class="keyword">
                    <a href="{{route('goods.index')}}?q={{$item}}">{{$item}}</a>
                </span>
            @endforeach
        </div>
    </div>
    <div class="cart-box float-left">
        <a href="{{route('cart.index')}}">
            <i class="iconfont icon-cart"></i>
            <span>@lang('html.cart')</span>
        </a>

    </div>
    <div class="app-download-box float-left">
        <i class="iconfont icon-mobile"></i>
        <span>@lang('html.app_download')</span>
    </div>
</div>
<div class="search-bar-fixed">
    <div class="container">
        <div class="logo float-left" >
            <a href="{{url('/')}}">
                <img src="{{storage_url(config('shop.logo_path'))}}">
            </a>
        </div>
        <div class="search-box float-left" id="search-bar">
            <div class="input">
                <input value="{{request()->input('q','')}}" id="keywords-fixed" placeholder="@lang('html.common.please_input_name')"  type="text">
                <span class="input-suffix">
                 <button  class="btn search-btn">@lang('html.search')</button>
            </span>

            </div>
        </div>
    </div>
</div>