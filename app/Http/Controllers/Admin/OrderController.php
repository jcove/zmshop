<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/7/12
 * Time: 11:00
 */

namespace App\Http\Controllers\Admin;


use App\Exceptions\CartException;
use App\Exceptions\PaymentException;
use App\Http\Requests\CreateOrderRequest;
use App\Models\Order;
use App\Models\OrderGoods;
use App\Services\CreateOrderService;
use App\Services\OrderService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Jcove\Admin\Facades\Admin;
use Jcove\Restful\Restful;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Spatie\Permission\Exceptions\UnauthorizedException;

/**
 * Class BuyController
 * @package App\Http\Controllers
 */
class OrderController
{
    use Restful;

    private $createOrderService;
    private $orderService;

    /**
     * OrderController constructor.
     * @param $createOrderService
     */
    public function __construct()
    {
        $this->model                                =   new Order();
        $this->orderService                         =   new OrderService();
    }


    /**
     * @param CreateOrderRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws CartException
     * @throws PaymentException
     */
    public function createOrder(CreateOrderRequest $request){
        $this->createOrderService                   =   new CreateOrderService();
        $this->createOrderService->setAddress($request->address_id);
        $order                                      =   $this->createOrderService->create();
        $this->data['data']                         =   $order;
        return $this->respond($this->data);
    }

    public function where(){
        $where                          =   [];


        if(($orderStatus = request()->order_status) !==null){

            if($orderStatusAction = request()->order_status_action){
                $this->model            =   $this->model->where('order_status',$orderStatusAction,$orderStatus);
            }else{
                $where['order_status']  =   $orderStatus;
            }
        }
        if($phone = request()->phone){
            $where['phone']         =   $phone;
        }
        if($order_sn = request()->order_sn){
            $where['order_sn']      =   $order_sn;
        }
        if($country = request()->country){
            $where['country']      =   $country;
        }
        if($province = request()->province){
            $where['province']      =   $province;
        }
        if($city = request()->city){
            $where['city']      =   $city;
        }
        if($district = request()->district){
            $where['district']      =   $district;
        }
        if($userId = request()->user_id){
            $where['user_id']      =   $userId;
        }
        if($consignee = request()->consignee){
            $this->model            =   $this->model->where('consignee','like','%'.$consignee.'%');
        }
        $admin                      =   Admin::user(config('admin.api_guard'));
        if($admin){
            if($admin->id != 1){
                $list                   =   OrderGoods::where('country',$admin->country)->pluck('order_id');
                $this->model            =   $this->model->whereIn('id',$list);
            }
        }else{
            throw UnauthorizedException::notLoggedIn();
        }



        return $where;
    }

    protected function beforeIndex(){
        if(request()->acceptsJson() && request()->ajax()){
            if($list = $this->data){
                if(count($list)){
                    foreach ($list as  $k=>$value){
                        $value->order_goods;

                        $value->user            ;
                        $list[$k]               =   $value;
                    }
                    $this->data         =   $list;
                }
            }
        }else{
            if($list = $this->data['list']){
                if(count($list)){
                    foreach ($list as  $k=>$value){
                        $value->order_goods;
                        $list[$k]               =   $value;
                    }
                    $this->data['list']         =   $list;
                }
            }
        }

        $this->setTitle(trans('html.order.order_list'));


    }
    protected function beforeShow(){
        if($this->canJson()){
            $this->model->order_goods;
        }
    }


    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \App\Exceptions\OrderException
     */
    public function pay($id){
        $this->orderService->pay($id);
        return $this->success();
    }

    public function shipping(){

    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \App\Exceptions\OrderException
     */
    public function confirm($id){
        $this->orderService->confirm($id);
        return $this->success();
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \App\Exceptions\OrderException
     */
    public function cancel($id){
        $this->orderService->cancel($id);
        return $this->success();
    }



    public function status(){
        $this->data                         =   Order::allStatusNumber(Auth::id());
        return $this->respond($this->data);
    }

    public function export(){
        $where                              =   $this->where();
        $list                               =   $this->model->where($where)->get();
        if($list){
            $this->data['list']             =   $list;
        }else{
            return $this->fail(trans('message.data_not_found'),404);
        }
        $filename                           =   '订单'. (new Carbon())->format('Y-m-d-H-i-s');
        return response()
            ->view('pc.admin.order.export', $this->data, 200)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Type', 'application/force-download')
            ->header('Content-Disposition', 'filename='.$filename.'.xls')
            ->header('Content-Type', 'application/vnd.ms-excel');
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportExcel(){
        $spreadSheet                        =   new Spreadsheet();
        $workSheet                          =   $spreadSheet->getActiveSheet();
        $workSheet->setTitle('订单');
        $workSheet->setCellValueByColumnAndRow(1,1,'订单编号');
        $workSheet->setCellValueByColumnAndRow(2,1,'买家会员');
        $workSheet->setCellValueByColumnAndRow(3,1,'支付金额');
        $workSheet->setCellValueByColumnAndRow(4,1,'商品名称');
        $workSheet->setCellValueByColumnAndRow(5,1,'商品代码');
        $workSheet->setCellValueByColumnAndRow(6,1,'规格代码');
        $workSheet->setCellValueByColumnAndRow(7,1,'规格名称');
        $workSheet->setCellValueByColumnAndRow(8,1,'数量');
        $workSheet->setCellValueByColumnAndRow(9,1,'价格');
        $workSheet->setCellValueByColumnAndRow(10,1,'商品备注');
        $workSheet->setCellValueByColumnAndRow(11,1,'运费');
        $workSheet->setCellValueByColumnAndRow(12,1,'买家留言');
        $workSheet->setCellValueByColumnAndRow(13,1,'收货人');
        $workSheet->setCellValueByColumnAndRow(14,1,'联系电话');
        $workSheet->setCellValueByColumnAndRow(15,1,'联系手机');
        $workSheet->setCellValueByColumnAndRow(16,1,'收货地址');
        $workSheet->setCellValueByColumnAndRow(17,1,'省');
        $workSheet->setCellValueByColumnAndRow(18,1,'市');
        $workSheet->setCellValueByColumnAndRow(19,1,'区');
        $workSheet->setCellValueByColumnAndRow(20,1,'邮编');
        $workSheet->setCellValueByColumnAndRow(21,1,'订单创建时间');
        $workSheet->setCellValueByColumnAndRow(22,1,'订单付款时间');
        $workSheet->setCellValueByColumnAndRow(23,1,'发货时间');
        $workSheet->setCellValueByColumnAndRow(24,1,'物流单号');
        $workSheet->setCellValueByColumnAndRow(25,1,'物流公司');
        $workSheet->setCellValueByColumnAndRow(26,1,'卖家备注');
        $workSheet->setCellValueByColumnAndRow(27,1,'发票种类');
        $workSheet->setCellValueByColumnAndRow(28,1,'发票类型');
        $workSheet->setCellValueByColumnAndRow(29,1,'发票抬头');
        $workSheet->setCellValueByColumnAndRow(30,1,'纳税人识别号');
        $workSheet->setCellValueByColumnAndRow(31,1,'开户行');
        $workSheet->setCellValueByColumnAndRow(32,1,'账号');
        $workSheet->setCellValueByColumnAndRow(33,1,'地址');
        $workSheet->setCellValueByColumnAndRow(34,1,'电话');
        $workSheet->setCellValueByColumnAndRow(35,1,'是否手机订单');
        $workSheet->setCellValueByColumnAndRow(36,1,'是否货到付款');
        $workSheet->setCellValueByColumnAndRow(37,1,'支付方式');
        $workSheet->setCellValueByColumnAndRow(38,1,'支付交易号');
        $workSheet->setCellValueByColumnAndRow(39,1,'真实姓名');
        $workSheet->setCellValueByColumnAndRow(40,1,'身份证号');
        $workSheet->setCellValueByColumnAndRow(41,1,'仓库名称');
        $workSheet->setCellValueByColumnAndRow(42,1,'预计发货时间');
        $workSheet->setCellValueByColumnAndRow(43,1,'预计送达时间');
        $workSheet->setCellValueByColumnAndRow(44,1,'订单类型');
        $workSheet->setCellValueByColumnAndRow(45,1,'是否分销商订单');

        $where                              =   $this->where();
        $list                               =   $this->model->where($where)->get();
        if($list && ($length = count($list)) > 0){
            $j                              =   2;
            for ($i=0; $i < $length; $i++ ){
                $row                        =   $list->offsetGet($i);
                foreach ($row->order_goods as $goods){
                    $workSheet->setCellValueByColumnAndRow(1,$j,$row->order_sn);
                    $workSheet->setCellValueByColumnAndRow(2,$j,'买家会员');
                    $workSheet->setCellValueByColumnAndRow(3,$j,$row->order_total);
                    $workSheet->setCellValueByColumnAndRow(4,$j,$goods->goods_name);
                    $workSheet->setCellValueByColumnAndRow(5,$j,$goods->goods->goods_sn);
                    $workSheet->setCellValueByColumnAndRow(6,$j,$goods->goods_spec_item_id);
                    $workSheet->setCellValueByColumnAndRow(7,$j,$goods->goods_spec_item_name);
                    $workSheet->setCellValueByColumnAndRow(8,$j,$goods->num);
                    $workSheet->setCellValueByColumnAndRow(9,$j,$goods->final_price);
                    $workSheet->setCellValueByColumnAndRow(10,$j,'');
                    $workSheet->setCellValueByColumnAndRow(11,$j,$row->shipping_fee);
                    $workSheet->setCellValueByColumnAndRow(12,$j,$row->remark);
                    $workSheet->setCellValueByColumnAndRow(13,$j,$row->consignee);
                    $workSheet->setCellValueByColumnAndRow(14,$j,$row->phone);
                    $workSheet->setCellValueByColumnAndRow(15,$j,$row->phone);
                    $workSheet->setCellValueByColumnAndRow(16,$j,$row->address.$row->country);
                    $workSheet->setCellValueByColumnAndRow(17,$j,$row->province);
                    $workSheet->setCellValueByColumnAndRow(18,$j,$row->city);
                    $workSheet->setCellValueByColumnAndRow(19,$j,$row->district);
                    $workSheet->setCellValueByColumnAndRow(20,$j,$row->zip_code);
                    $workSheet->setCellValueByColumnAndRow(21,$j,$row->created_at);
                    $workSheet->setCellValueByColumnAndRow(22,$j,$row->pay_time);
                    $workSheet->setCellValueByColumnAndRow(23,$j,$row->shipping_time);
                    $workSheet->setCellValueByColumnAndRow(24,$j,$row->express_sn);
                    $workSheet->setCellValueByColumnAndRow(25,$j,$row->express_name);
                    $workSheet->setCellValueByColumnAndRow(26,$j,'');
                    $workSheet->setCellValueByColumnAndRow(27,$j,'');
                    $workSheet->setCellValueByColumnAndRow(28,$j,'');
                    $workSheet->setCellValueByColumnAndRow(29,$j,'');
                    $workSheet->setCellValueByColumnAndRow(30,$j,'');
                    $workSheet->setCellValueByColumnAndRow(31,$j,'');
                    $workSheet->setCellValueByColumnAndRow(32,$j,'');
                    $workSheet->setCellValueByColumnAndRow(33,$j,'');
                    $workSheet->setCellValueByColumnAndRow(34,$j,'');
                    $workSheet->setCellValueByColumnAndRow(35,$j,'');
                    $workSheet->setCellValueByColumnAndRow(36,$j,'');
                    $workSheet->setCellValueByColumnAndRow(37,$j,$row->pay_code);
                    $workSheet->setCellValueByColumnAndRow(38,$j,'');
                    $workSheet->setCellValueByColumnAndRow(39,$j,'');
                    $workSheet->setCellValueByColumnAndRow(40,$j,'');
                    $workSheet->setCellValueByColumnAndRow(41,$j,'');
                    $workSheet->setCellValueByColumnAndRow(42,$j,'');
                    $workSheet->setCellValueByColumnAndRow(43,$j,'');
                    $workSheet->setCellValueByColumnAndRow(44,$j,'');
                    $workSheet->setCellValueByColumnAndRow(45,$j,'');
                    $j++;
                }

            }
        }

        $filename                           =   '订单'. (new Carbon())->format('Y-m-d-H-i-s').'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');
        $writer->save('php://output');
    }

} 