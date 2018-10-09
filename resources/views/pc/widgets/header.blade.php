
<div class="top-menu-tools">
    <div class="container" style="padding-bottom: 0">
        <ul>
            <li class="float-left index">
                <a href="{{url('/')}}">
                    <span class="iconfont icon-home"></span><span>@lang('html.index')</span>
                </a>
            </li>

            <li class="float-right">
                <a href="{{route('collection.index')}}">
                    @lang('html.my_collect')
                </a>
            </li>
            <li class="float-right">
                <a href="{{route('order.index')}}">
                    @lang('html.my_order')
                </a>
            </li>
            <li class="float-right user-login-register">
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
