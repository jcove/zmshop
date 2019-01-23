<template>
    <div class="order-list">
        <div class="filters">
            <ul>
                <li v-for="item in filters" :class="{active:item.active}" @click="filter(item)">
                    <span>{{item.label}}</span>
                    <span class="num">({{item.num}})</span>
                </li>
            </ul>
        </div>
        <div class="title">
            <div class="name">

            </div>
        </div>
        <div class="list">
            <div class="order" v-for="(order,index) in list">
                <div class="order-sn">
                    <b>{{order.created_at}}</b> &nbsp;&nbsp;{{$t('order.order_sn')}}:{{order.order_sn}}
                    &nbsp;&nbsp;{{$t('order.total')}} {{order.total_amount}}({{$t('order.shipping_fee')}} {{order.shipping_fee}})
                </div>
                <div class="order-info">
                    <div class="goods-title">
                        <div class="name">
                            {{$t('goods.name')}}
                        </div>
                        <div class="price">
                            {{$t('goods.price')}}
                        </div>
                        <div class="num">
                            {{$t('goods.number')}}
                        </div>
                        <div class="total">
                            {{$t('cart.goods_total')}}
                        </div>
                    </div>
                    <div class="order-goods">
                        <div class="goods" v-for="goods in order.order_goods">
                            <div class="cover">
                                <a  :href="'goods/'+goods.goods_id" target="_blank">
                                <img :src="goods.cover">
                                </a>
                            </div>
                            <div class="name">
                                <a  :href="'goods/'+goods.goods_id" target="_blank">
                                <p>{{goods.goods_name}}</p>
                                </a>
                                <p v-if="goods.goods_spec_item_name">{{goods.goods_spec_item_name}}</p>
                            </div>
                            <div class="price">
                                {{$t('goods.$')}}{{goods.price}}
                            </div>
                            <div class="num">
                                {{goods.num}}
                            </div>
                            <div class="after_service" @click="showReturnDialog(order.id,goods.goods_id,goods.goods_spec_item_id,goods.goods_name,goods.num*goods.final_price)" v-if="!goods.is_return && order.order_status  > 2">
                                {{$t('order.after_service')}}
                            </div>
                        </div>
                    </div>

                    <div class="info">
                        <div class="total">
                            {{order.goods_amount}}
                        </div>
                        <div class="status">
                            <p>{{orderStatus(order.order_status)}}</p>
                            <el-button type="text" @click="expressInfo(order.id,order.express_code,order.express_sn,order.express_name)">
                                {{$t('order.express_info')}}
                            </el-button>
                        </div>
                        <div class="operate">
                            <el-button @click="handleOperate(index,order)" v-if="operateShow(order.order_status)" type="primary"
                                       :disabled="operateDisable(order.order_status)">
                                {{orderOperate(order.order_status)}}
                            </el-button>

                            <el-button @click="cancelOrder(index,order)" v-if="order.order_status === 0" type="danger" style="margin-top: 10px;margin-left: 0">
                                {{$t('order.cancel')}}
                            </el-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="block">
            <el-pagination
                    layout="prev, pager, next"
                    :total="total" :page-size="pageSize"
                    @current-change="currentChange"
                    @prev-click="prevClick"
                    @next-click="nextClick">
            </el-pagination>
        </div>
        <el-dialog :visible.sync="expressDialogVisible">
            <express :order-id="orderId"></express>
        </el-dialog>
        <el-dialog :visible.sync="deliveryDialogVisible" @closed="onDeliveryClose">
            <delivery :order-id="orderId"></delivery>
        </el-dialog>
        <el-dialog :visible.sync="returnDialogVisible">
            <el-form>
                <el-form-item :label="$t('goods.name')" label-width="120px">
                    {{returnGoodsName}} {{$t('goods.$')}}:{{returnGoodsMoney}}
                </el-form-item>
                <el-form-item :label="$t('order.return_type')" label-width="120px">
                    <el-select v-model="returnForm.type">
                        <el-option v-for="option in options" :key="option.value" :label="option.label" :value="option.value">

                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label-width="120px" :label="$t('order.refund_money')">
                    <el-input  v-model="returnForm.refund_money">

                    </el-input>
                </el-form-item>
                <el-form-item label-width="120px" :label="$t('order.reason')">
                    <el-input type="textarea" :rows="3" v-model="returnForm.reason">

                    </el-input>
                </el-form-item>
                <el-form-item label-width="120px" >
                   <el-button type="success" @click="returnGoods">{{$t('operate.submit')}}</el-button>
                </el-form-item>
            </el-form>
        </el-dialog>
        <template v-if="showPayLoading">
            <go-pay-message :id="orderId">

            </go-pay-message>
        </template>
    </div>
</template>

<script>
    import orderApi from '../api/order';
    import returnGoodsApi from "../api/returnGoods";
    import config from "../config";
    import GoPayMessage from "./goPayMessage";
    import Delivery from "./pc/delivery";


    const CREATED = 0;//待付款，创建
    const PAID = 2;//已支付，等待发货
    const SHIPPED = 4;//已发货，等待确认
    const CONFIRMED = 6;//已确认，等待评价，已完成
    const COMMENTED = 8;//已评价
    const CANCELED    =   -1;
    const FINISH = 8;
    export default {
        components: {
            Delivery,
            GoPayMessage},
        name: "order-list",
        data() {
            const that  =   this;
            return {
                active: '',
                express_sn:'',
                express_code:'',
                express_name:'',
                expressDialogVisible:false,
                showPayLoading: false,
                deliveryDialogVisible: false,
                orderId:0,
                filters: [
                    {
                        label: this.$t('order.all_order'),
                        num: 0,
                        active: true,
                        status:null
                    },
                    {
                        label: this.$t('order.wait_pay'),
                        num: 0,
                        active: false,
                        status:CREATED
                    },
                    {
                        label: this.$t('order.wait_delivery'),
                        num: 0,
                        active: false,
                        status:PAID
                    },
                    {
                        label: this.$t('order.wait_confirm'),
                        num: 0,
                        active: false,
                        status:SHIPPED
                    },
                    {
                        label: this.$t('order.wait_comment'),
                        num: 0,
                        active: false,
                        status:CONFIRMED
                    },
                    {
                        label: this.$t('order.finish'),
                        num: 0,
                        active: false,
                        status:COMMENTED
                    }
                ],
                list: [],

                params: {
                    order_status: null,
                    page: 1
                },
                options:[
                    {
                        label: that.$t('order.return_goods'),
                        value: 1
                    },
                    {
                        label: that.$t('order.refund'),
                        value: 2
                    },
                    {
                        label: that.$t('order.exchange_goods'),
                        value: 3
                    }
                ],
                returnForm:{
                    order_id:0,
                    goods_id:0,
                    reason:'',
                    type:3,
                    goods_spec_item_id:0,
                    refund_money:0
                },
                returnGoodsName:'',
                returnGoodsMoney:0,
                returnDialogVisible:false
            }
        },
        props: {
            orders: {
                type: Array,
                default: []
            },
            total: 0,
            pageSize: 0,
        },
        created() {
            this.list = this.orders;
            this.getStatus();
        },
        methods: {
            filter(filter){
                if(!filter.active){
                    const that              =   this;
                    this.filters.forEach(function (item) {
                        const index         =   that.filters.indexOf(item);
                        item.active         =   false;
                        that.$set(that.filters,index,item);
                    })
                    const indexF            =   this.filters.indexOf(filter)
                    filter.active           =   true;
                    this.$set(this.filters,indexF,filter);
                    this.params.order_status = filter.status;
                    this.getList();
                    this.index              =   indexF;
                    this.currentFilter      =   filter
                }


            },
            orderOperate(status) {
                const CREATED = 0;//待付款，创建
                const PAID = 2;//已支付，等待发货
                const SHIPPED = 4;//已发货，等待确认
                const CONFIRMED = 6;//已确认，等待评价，已完成
                let text = '';
                switch (status) {
                    case CREATED:
                        text = this.$t('order.go_pay');
                        break;
                    case PAID:
                        text = this.$t('order.wait_delivery');
                        break;
                    case SHIPPED:
                        text = this.$t('order.go_confirm');
                        break;
                    case CONFIRMED:
                        text = this.$t('order.go_comment');
                        break;
                    case CANCELED:
                        text = this.$t('order.canceled');
                        break;
                    default:
                        text = this.$t('order.unknown')
                }
                return text;
            },
            orderStatus(status) {

                let text = '';
                switch (status) {
                    case CREATED:
                        text = this.$t('order.wait_pay');
                        break;
                    case CANCELED:
                        text = this.$t('order.canceled');
                        break;
                    case PAID:
                        text = this.$t('order.wait_delivery');
                        break;
                    case SHIPPED:
                        text = this.$t('order.wait_confirm');
                        break;
                    case CONFIRMED:
                        text = this.$t('order.wait_comment');
                        break;
                    case COMMENTED:
                        text = this.$t('order.finish');
                        break;
                    default:
                        text = this.$t('order.unknown')
                }
                return text;
            },

            operateDisable(status) {
                let disable = true;
                switch (status) {
                    case CREATED:
                        disable = false;
                        break;
                    case SHIPPED:
                        disable = false;
                        break;
                    case CONFIRMED:
                        disable = false;
                        break;
                    case CANCELED:
                        disable = true;
                        break;
                    default:
                        break;
                }
                return disable;

            },
            operateShow(status) {
                let show = true;
                switch (status) {
                    case CANCELED:
                        show = false;
                        break;
                    case FINISH:
                        show = false;
                        break;
                    default:
                        break;
                }
                return show;

            },
            handleOperate(index, order) {
                switch (order.order_status) {
                    case CREATED:
                        this.pay(order);
                        break;
                    case SHIPPED:
                        this.confirm(index, order);
                        break;
                    case CONFIRMED:
                        this.comment(index, order);
                        break;

                    default:
                        break;
                }
            },
            confirm(index, order) {
               this.deliveryDialogVisible = true
                this.orderId = order.id
            },
            pay(order) {
                location.href = config.baseApi+'/order/success/'+order.id;
            },
            comment(index, order) {
                location.href = config.baseApi + '/order/comment/' + order.id;
            },
            cancelOrder(index, order){
                orderApi.cancel(order.id).then(response => {
                    this.$message({
                        message: this.$t('common.success'),
                        type: 'success'
                    });
                    order.order_status = CANCELED;
                    this.$set(this.list, index, order);
                });
            },
            getList() {
                const that = this;
                orderApi.list(this.params).then(response => {
                    if (response) {
                        that.list           = response.data;
                        that.params.page    = response.current_page;
                        that.total          =   response.total;
                    }
                });
            },
            currentChange(page) {
                this.params.page = page;
                this.getList();
            },
            prevClick(page) {
                this.params.page = page;
                this.getList();
            },
            nextClick(page) {
                this.params.page = page;
                this.getList();
            },
            getStatus(){
                orderApi.status().then(response => {
                   if(response){
                       const keys               =   Object.keys(this.filters);
                       const that               =   this;
                       let total                =   0;
                       response.forEach(function(status){
                           keys.forEach(function (key) {
                               if(that.filters[key].status===status.order_status){
                                   that.filters[key].num    =   status.num
                               }
                           })
                           total+=parseInt(status.num);
                       });
                       this.filters[0].num      =   total;
                   }
                });
            },
            expressInfo(orderId,code,sn,name){
                this.express_code               =   code;
                this.express_sn                 =   sn;
                this.express_name               =   name;
                this.orderId                    =   orderId;
                this.expressDialogVisible       =   true;
            },
            showReturnDialog(orderId,goodsId,specId,goodsName,money){
                this.returnGoodsName = goodsName;
                this.returnGoodsMoney= money;
                this.returnForm.order_id = orderId;
                this.returnForm.goods_id = goodsId;
                this.returnForm.goods_spec_item_id = specId;
                this.returnForm.refund_money = 0;
                this.returnDialogVisible = true;
            },
            returnGoods(){
                const that =this
                returnGoodsApi.save(this.returnForm).then(response => {
                    if(response){
                        that.$message({
                            message:that.$t('order.return_success'),
                            type:'success'
                        })
                        that.returnDialogVisible = false;
                        this.returnGoodsName = '';
                        this.returnForm.order_id = 0;
                        this.returnForm.goods_id = 0;
                        this.returnForm.goods_spec_item_id = 0;
                        this.returnForm.refund_money = 0;
                        this.returnForm.reason = '';
                    }
                })
            },
            hasReturn(orderId,goodsId,specId){
                returnGoodsApi.check({order_id:orderId,goods_id:goodsId,goods_spec_item_id:specId}).then(response => {
                    if(response){
                        return true
                    }else{
                        return false
                    }
                })
            },
            onDeliveryClose(){
                this.getList()
            }
        }

    }
</script>

<style scoped lang="scss">
    @import "../../sass/variables";
    .order-list {
        padding: 10px 30px;
        .filters {
            li {
                text-align: center;
                padding: 5px;
                float: left;
                background-color: #e6e6e6;
                margin-right: 5px;
                cursor: pointer;
                .num {
                    color: red;
                }
                &.active {
                    background-color: $navbar-default-bg;
                    color: white;
                    .num {
                        color: white;
                    }
                }
            }
        }
        .list {
            .order {

                margin-top: 10px;
                overflow: hidden;
                .order-sn {
                    background-color: #e6e6e6;
                    padding: 10px;

                }
                .order-info {
                    border: 1px solid $border-default;
                    overflow: hidden;
                    .order-goods {
                        width: 500px;
                        float: left;
                        .goods {
                            text-align: center;
                            overflow: hidden;
                            height: 130px;
                            border-bottom: 1px solid #e8e8e8;
                            border-right: 1px solid #e8e8e8;
                            .name,.price,.num,.after_service{
                                display: flex;
                                align-items: center;
                                margin-top: 12px;
                            }
                            .cover {
                                float: left;
                                padding: 15px;
                                img {
                                    width: 100px;
                                    height: 100px;
                                }
                            }
                            .name {
                                width: 100px;
                                float: left;
                                height: 100px;
                                overflow: hidden;
                                padding: 0;
                                margin-top: 10px;
                                p {
                                    line-height: 25px;
                                    height: 100px;
                                }
                            }
                            .price, .num {
                                width: 100px;
                                float: left;
                                line-height: 25px;
                            }
                            .after_service{
                                cursor: pointer;
                                line-height: 25px;
                                &:hover{
                                    color:#35a0fc;
                                }
                            }
                        }
                    }
                    .info {
                        text-align: center;
                        width: 330px;
                        float: left;
                        display: flex;
                        height: 130px;
                        .total, .status {
                            width: 110px;
                            float: left;
                            line-height: 40px;
                            text-align: center;
                            margin-top: 5px;

                        }
                        .operate {
                            width: 110px;
                            float: left;
                            margin-top: 12px;
                        }
                    }
                }
            }
        }
    }
    .goods-title{
        overflow: hidden;
        border-bottom: 1px solid #e8e8e8;
        line-height: 40px;
        .name{
            width: 230px;
            float: left;
            text-align: center;
        }
        .price{
            width: 100px;
            float: left;
        }
        .num{
            width: 170px;
            float: left;
        }
        .total{
            width: 100px;
            float:left;
            text-align:center;
        }
    }

</style>