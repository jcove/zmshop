<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/7/18
 * Time: 16:58
 */

namespace App\Http\Controllers;


use App\Exceptions\OrderException;
use App\Exceptions\ParamException;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Order;
use App\Models\OrderGoods;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jcove\Restful\Restful;

class CommentController extends Controller
{
    use Restful;

    /**
     * CommentController constructor.
     */
    public function __construct()
    {
        $this->model                                =   app('\App\Models\Comment');
    }


    /**
     * @param CommentRequest $request
     * @throws OrderException
     * @return mixed
     */
    public function store(CommentRequest $request){

        $order                                      =   $request->order;
        if($order->order_status!= Order::CONFIRMED){
            throw new OrderException(trans('order.order_status_not_allowed').':'.Order::orderStatusText($order->order_status));
        }


        $comments                                   =   $request->comments;
        $orderGoods                                 =   $order->order_goods;
        if(count($comments) != count($orderGoods)){
            throw new OrderException(trans('order.all_goods_should_commented'));
        }
        DB::transaction(function () use ($comments,$orderGoods,$request){
            foreach ($comments as $comment){
                $Comment                            =   new Comment();
                $Comment->goods_id                  =   $comment['goods_id'];
                $Comment->content                   =   $comment['content'];
                $Comment->user_id                   =   Auth::id();
                $Comment->nick                      =   Auth::user()->nick;
                $Comment->images                    =   !empty($comment['images']) ? implode(',',$comment['images']) : '';
                $Comment->express_rank              =   $comment['express_rank'];
                $Comment->goods_rank                =   $comment['goods_rank'];
                $Comment->service_rank              =   $comment['service_rank'];
                $Comment->order_id                  =   $request->order_id;
                $Comment->is_anonymous              =   $request->is_anonymous ? : 1;
                $Comment->save();
            }
            OrderGoods::where('order_id',$request->order_id)->update(['is_comment'=>OrderGoods::COMMENTED]);
            Order::where('id',$request->order_id)->update(['order_status'=>Order::COMMENTED]);
        });
        return $this->success();

    }

    /**
     * @throws ParamException
     */
    protected function where(){
        $goodsId                                    =   request()->goods_id;
        if(empty($goodsId)){
            throw new ParamException(trans('message.goods_id_must'));
        }
        $where['goods_id']                          =   $goodsId;
        return $where;
    }

    public function rank($goods_id){
        $this->data['rank']                                 =   Comment::where('goods_id',$goods_id)->avg('goods_rank');
        return $this->respond($this->data);
    }

    public function user(){
        $list                                               =   Comment::where('user_id',Auth::id())->paginate(config('restful.page_rows'));
        $this->data['list']                                 =   $list;
        $this->setTitle(trans('html.user.user_comment'));
        return $this->respond($this->data);
    }
}