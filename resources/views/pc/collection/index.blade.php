@extends('pc.user.base')

@section('main')
    <div class="my-collections">
        <div class="title">
            <div class="cover">
               @lang('html.goods.cover')
            </div>
            <div class="name">
                @lang('html.goods.name')
            </div>
            <div class="date">
                @lang('html.collection.datetime')
            </div>
            <div class="operate">
                <a>@lang('html.common.operate')</a>
            </div>
        </div>
        <div class="list">
            <ul>
               @foreach($list as $item)
                   <li>
                       <div class="cover">
                           <a href="{{route('goods.show',['id'=>$item->goods_id])}}">
                               <img src="{{$item->cover}}">
                           </a>

                       </div>
                       <div class="name">
                           <a href="{{route('goods.show',['id'=>$item->goods_id])}}">
                               <p>{{$item->goods_name}}</p>
                           </a>
                       </div>
                       <div class="date">
                           {{$item->created_at}}
                       </div>
                       <div class="operate">
                           <a href="javascript:deleteCollection({{$item->id}});">@lang('html.operate.delete')</a>
                       </div>
                   </li>
               @endforeach
            </ul>
        </div>
        <div class="pages">
            {{$list->links()}}
        </div>
    </div>
@endsection
