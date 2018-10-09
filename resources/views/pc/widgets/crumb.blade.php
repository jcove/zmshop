<div class="container crumb">
    @foreach($crumb as $item)
        @if ($loop->last)
            <span>{{$item['title']}}</span>
        @else
            <a href="{{$item['url']}}">
                <span>{{$item['title']}}</span><span class="separator">{{$item['separator']}}</span>
            </a>
        @endif
    @endforeach
</div>