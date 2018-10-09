<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<table>
    <tr>
        <th>
            订单号
        </th>
        <th>
            下单时间
        </th>
        <th>
            订单状态
        </th>
        <th>
            付款状态
        </th>
        <th>
            付款时间
        </th>
        <th>
            发货状态
        </th>
        <th>
            发货时间
        </th>
        <th>
            收货人
        </th>
        <th>
            联系电话
        </th>
        <th colspan="5">
            收货地址
        </th>
        <th>
            订单商品
        </th>
        <th>
            价格
        </th>
        <th>
            规格
        </th>
        <th>
            数量
        </th>
    </tr>
    @foreach($list as $row)
        @foreach($row->order_goods as $goods)
            @if($loop->first)
                <tr>
                    <td class="" rowspan=" {{count($row->order_goods)}}">
                        {{$row->order_sn}}&nbsp;
                    </td>
                    <td class="" rowspan=" {{count($row->order_goods)}}">
                        {{$row->created_at}}&nbsp;
                    </td>
                    <td class="" rowspan=" {{count($row->order_goods)}}">
                        @switch($row->order_status)
                            @case (-1)
                            已取消
                            @break
                            @case (0)
                            待付款
                            @break
                            @case (2)
                            待发货
                            @break
                            @case (4)
                            待确认
                            @break
                            @case (6)
                            待评价
                            @break
                            @case (8)
                            已评价
                            @break
                        @endswitch
                    </td>
                    <td class="" rowspan=" {{count($row->order_goods)}}">
                        @switch($row->pay_status)

                            @case (0)
                            待付款
                            @break
                            @case (1)
                            已付款
                            @break

                        @endswitch
                    </td>
                    <td class="" rowspan=" {{count($row->order_goods)}}">
                       {{$row->pay_time}}
                    </td>
                    <td class="" rowspan=" {{count($row->order_goods)}}">
                        @switch($row->shipping_status)

                            @case (0)
                            未发货
                            @break
                            @case (1)
                            部分发货
                            @break

                            @case (2)
                            全部发货
                            @break

                        @endswitch
                    </td>
                    <td rowspan=" {{count($row->order_goods)}}">
                        {{$row->shipping_time}}
                    </td>
                    <td rowspan=" {{count($row->order_goods)}}">
                        {{$row->consignee}}
                    </td>
                    <td rowspan=" {{count($row->order_goods)}}">
                        {{$row->phone}}
                    </td>
                    <td rowspan=" {{count($row->order_goods)}}">
                        {{$row->country}}
                    </td>
                    <td rowspan=" {{count($row->order_goods)}}">
                        {{$row->province}}
                    </td>
                    <td rowspan=" {{count($row->order_goods)}}">
                        {{$row->city}}
                    </td>
                    <td rowspan=" {{count($row->order_goods)}}">
                        {{$row->district}}
                    </td>
                    <td rowspan=" {{count($row->order_goods)}}">
                        {{$row->address}}
                    </td>
                    <td>
                        {{$goods->goods_name}}
                    </td>
                    <td>
                        {{$goods->final_price}}
                    </td>
                    <td>
                        {{$goods->goods_spec_item_name}}
                    </td>
                    <td>
                        {{$goods->num}}
                    </td>
                </tr>
            @else
                <tr>
                    <td>
                        {{$goods->goods_name}}
                    </td>
                    <td>
                        {{$goods->final_price}}
                    </td>
                    <td>
                        {{$goods->goods_spec_item_name}}
                    </td>
                    <td>
                        {{$goods->num}}
                    </td>
                </tr>
            @endif

        @endforeach

    @endforeach
</table>
<style>
    table{
        font-family: "Helvetica Neue", Helvetica, "PingFang SC", "Hiragino Sans GB", "Microsoft YaHei", "微软雅黑", Arial, sans-serif;
    }
    tr{
        background-color: #999999;
    }
    td {
        background-color: white;
        font-size: 14px;
        border: 1px solid #999999;
        text-align: center;
    }
</style>
</html>
