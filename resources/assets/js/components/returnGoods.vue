<template>
    <div class="order-list">
        <div class="title">
            <div class="name">

            </div>
        </div>
        <div class="list">
            <div class="order" v-for="(order,index) in list">
                <div class="order-sn">
                    {{$t('order.return_sn')}}:{{order.return_sn}}
                </div>
                <div class="order-info">
                    <div class="order-goods">
                        <div class="goods">
                            <div class="cover">
                                <img :src="order.cover">
                            </div>
                            <div class="name">
                                <p>{{order.goods_name}}</p>
                                <p v-if="order.goods_spec_item_name">{{order.goods_spec_item_name}}</p>
                            </div>
                            <div class="price">
                                {{$t('order.refund_money')}}{{$t('goods.$')}}{{order.refund_money}}
                            </div>

                        </div>
                    </div>

                    <div class="info">
                        <div class="status">
                            {{orderStatus(order.status)}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="block" v-if="total > pageSize">
            <el-pagination
                    layout="prev, pager, next"
                    :total="total" :page-size="pageSize"
                    @current-change="currentChange"
                    @prev-click="prevClick"
                    @next-click="nextClick">
            </el-pagination>
        </div>
    </div>
</template>

<script>
    import orderApi from '../api/order';
    import api from "../api";
    import returnGoodsApi from "../api/returnGoods";


    const CREATED = 0;//创建
    const AGREE = 1;//同意
    const REFUSED = 2;//取消
    const FINISH = 3;//已完成
    const CANCELED    =   -1;
    export default {
        name: "return-goods",
        data() {
            const that  =   this;
            return {
                active: '',
                express_sn:'',
                shipping_code:'',
                shipping_name:'',
                expressDialogVisible:false,
                list: [],

                params: {
                    status: null,
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
        },
        methods: {
            orderStatus(status) {

                let text = '';
                switch (status) {
                    case CREATED:
                        text = this.$t('order.wait_approval');
                        break;
                    case CANCELED:
                        text = this.$t('order.canceled');
                        break;
                    case AGREE:
                        text = this.$t('order.agree');
                        break;
                    case FINISH:
                        text = this.$t('order.finish');
                        break;
                    case REFUSED:
                        text = this.$t('order.refused');
                        break;
                    default:
                        text = this.$t('order.unknown');
                        break;
                }
                return text;
            },

            operateDisable(status) {
                let disable = true;
                switch (status) {
                    case CREATED:
                        disable = false;
                        break;
                    default:
                        break;
                }

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


            getList() {
                const that = this;
                returnGoodsApi.list(this.params).then(response => {
                    if (response) {
                        that.list           =   response.data;
                        that.params.page    =   response.current_page;
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
            }
        }

    }
</script>

<style scoped lang="scss">
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
                    background-color: #1b7dd1;
                    color: white;
                    .num {
                        color: white;
                    }
                }
            }
        }
        .list {
            .order {
                border: 1px solid #35a0fc;
                margin-top: 10px;
                overflow: hidden;
                .order-sn {
                    background-color: #e6e6e6;
                    padding: 10px;

                }
                .order-info {
                    .order-goods {
                        width: 500px;
                        float: left;
                        .goods {
                            text-align: center;
                            overflow: hidden;
                            height: 130px;
                            border-bottom: 1px solid #e8e8e8;
                            border-right: 1px solid #e8e8e8;
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
                                p {
                                    line-height: 40px;
                                }
                            }
                            .price{
                                width: 200px;
                                float: left;
                                line-height: 100px;
                            }
                            .after_service{
                                cursor: pointer;
                                line-height: 100px;
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
                        .total, .status {
                            padding-top: 30px;
                            width: 110px;
                            float: left;
                            line-height: 40px;
                            text-align: center;
                        }
                        .operate {
                            padding-top: 30px;
                            width: 110px;
                            float: left;
                        }
                    }
                }
            }
        }
    }
</style>