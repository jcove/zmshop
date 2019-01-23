<div class="goods-category-tree">
    <div class="title">
        @lang('html.goods_category.all')
    </div>
    @if(count($goods_category_tree) > 0)
        @foreach($goods_category_tree as $category)

            <div class="category">
                <div class="name">
                    <a href="{{route('goods.index',['category_id'=>$category->id])}}"> {{$loop->index +1}}F {{$category->name}}</a>
                </div>

                <div class="subs">
                    @if(count($category->child) > 0)
                        <ul>
                            @foreach($category->child as $item)
                                <li class="item">
                                    <a href="{{route('goods.index',['category_id'=>$item->id])}}">{{$item->name}}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

        @endforeach
    @endif

</div>
