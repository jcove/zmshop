
<div class="top-menu-tools">
    <div class="container" style="padding-bottom: 0">
        <ul>
            {{--<li class="float-left index">--}}
                {{--<a href="{{url('/')}}">--}}
                    {{--<span class="iconfont icon-home"></span><span>@lang('html.index')</span>--}}
                {{--</a>--}}
            {{--</li>--}}

            <li class="float-left sis">
                <el-dropdown>
                  <span class="el-dropdown-link" style="cursor: pointer">
                    @lang('html.common.goods_sn_search')<i class="el-icon-arrow-down el-icon--right"></i>
                  </span>
                    <el-dropdown-menu slot="dropdown">
                        <el-dropdown-item>
                            <p style="line-height: 30px">
                                <span><img src="{{asset('images/jp.png')}}" style="vertical-align:middle"></span>
                                <span>@lang('html.common.japan')</span>
                                <span><a href="https://gs1.org">https://gs1.org</a></span>
                            </p>
                        </el-dropdown-item>
                        <el-dropdown-item>
                            <p>
                                <img src="{{asset('images/korea.png')}}" style="vertical-align:middle">
                                <span>@lang('html.common.korea')</span>
                                <span><a href="https://gs1.org">https://gs1.org</a></span>
                            </p>
                        </el-dropdown-item>
                        <el-dropdown-item>
                            <p>
                                <img src="{{asset('images/thailand.png')}}" style="vertical-align:middle">
                                <span>@lang('html.common.thailand')</span>
                                <span><a href="https://gs1.org">https://gs1.org</a></span>
                            </p>
                        </el-dropdown-item>
                        <el-dropdown-item>
                            <p>
                                <img src="{{asset('images/india.png')}}" style="vertical-align:middle">
                                <span>@lang('html.common.india')</span>
                                <span><a href="https://gs1.org">https://gs1.org</a></span>
                            </p>
                        </el-dropdown-item>
                        <el-dropdown-item>
                            <p>
                                <img src="{{asset('images/america.jpg')}}" style="vertical-align:middle">
                                <span>@lang('html.common.america')</span>
                                <span><a href="https://gs1.org">https://gs1.org</a></span>
                            </p>
                        </el-dropdown-item>

                    </el-dropdown-menu>
                </el-dropdown>
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
