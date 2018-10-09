<div class="user-menus">
    <ul>
        <li class="item level-1">
            <i class="iconfont icon-arrow-left">&nbsp;</i><a class="" href="{{route('order.index')}}">@lang('html.user.trading_center')</a>
        </li>
        <li class="item level-2">
            <a class="" href="{{route('order.index')}}">@lang('html.user.user_order')</a>
        </li>
        <li class="item level-2">
            <a class="" href="{{route('returnGoods.index')}}">@lang('html.user.user_return_goods')</a>
        </li>
        <li class="item level-2">
            <a class="" href="{{route('collection.index')}}">@lang('html.user.user_collection')</a>
        </li>
        <li class="item level-2">
            <a class="" href="{{route('comment.user')}}">@lang('html.user.user_comment')</a>
        </li>
        <li class="item level-1">
            <i class="iconfont icon-arrow-left">&nbsp;</i><a class="" href="{{route('user.my')}}">@lang('html.user.base_info')</a>
        </li>
        <li class="item level-1">
            <i class="iconfont icon-arrow-left">&nbsp;</i>@lang('html.user.setting')
        </li>
        <li class="item level-2">
            <a href="{{route('user.safe')}}">@lang('html.user.safe_info')</a>
        </li>
        <li class="item level-2">
           <a href="{{route('address.index')}}">@lang('html.user.address')</a>
        </li>
        <li class="item level-2">
            <a href="{{route('suggestion.create')}}">@lang('html.user.feedback')</a>
        </li>
    </ul>
</div>