<div class="nav-bar">
    <div class="container">
        <ul>
            <li class="nav-item">
                <a>
                    <i class="iconfont icon-menu"></i>
                    <span>
                    @lang('html.all_goods')
                </span>
                </a>
            </li>
            @foreach($navs as $nav)
                <li class="nav-item">
                    <a href="{{$nav->url}}">{{$nav->name}}</a>
                </li>
            @endforeach
        </ul>
    </div>

</div>