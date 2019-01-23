
<div class="top-menu-tools">
    <div class="container" style="padding-bottom: 0">
        <ul>
            <li class="float-left index">
                <a href="{{url('/')}}">
                    <span class="iconfont icon-home"></span><span>@lang('html.index')</span>
                </a>
            </li>
            <li class="float-right">
                @lang('html.customer_service_phone')
                <span class="phone">{{config('shop.customer_service_phone')}}</span>
            </li>
            <li class="float-right">
                <a href="javascript:clearLanguage()">
                    @lang('html.change_lang')
                </a>
            </li>
            <li class="float-right  @if(route('collection.index') == url()->full()) active @endif">
                <a href="{{route('collection.index')}}">
                    @lang('html.my_collect')
                </a>
            </li>
            <li class="float-right @if(route('order.index') == url()->full()) active @endif"  >
                <a href="{{route('order.index')}}">
                    @lang('html.my_order')
                </a>
            </li>
            <li class="float-right  @if(route('user.my') == url()->full()) active @endif">
                @if(Auth::check())
                    <a href="{{route('user.my')}}">{{Auth::user()->nick}}</a>/
                    <a href="{{route('user.logout')}}">@lang('html.logout')</a>
                @else
                    <a href="{{route('user.showRegistrationForm')}}">@lang('html.register')</a>/
                    <a href="{{route('user.showLoginForm')}}">@lang('html.login')</a>
                @endif
            </li>
        </ul>
    </div>
</div>
