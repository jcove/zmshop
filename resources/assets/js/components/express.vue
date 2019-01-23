<template>
    <div class="">
        <div>
            {{$t('order.delivery_sn')}}:
            <template v-if="deliveries.length > 0">

                <span v-for="(delivery,index) in deliveries" @click="tagClick(index)" style="cursor: pointer;margin-left: 20px">
                    <el-tag :type="delivery.type">
                   {{countryText(delivery.depot)}}{{$t('order.delivery_depot')}}:{{delivery.delivery_sn}}
                </el-tag>
                </span>
            </template>
            <template v-else>
                {{$t('order.no_delivery')}}:
            </template>


        </div>
        <div class="order-info" v-loading="loading">
            <div class="order-goods">
                <div class="goods" v-for="goods in delivery.delivery_goods">
                    <div class="name">
                        <a :href="'goods/'+goods.goods_id" target="_blank">
                            <span>{{goods.goods_name}}</span>
                            <span v-if="goods.goods_spec_item_name">{{goods.goods_spec_item_name}}</span>
                        </a>
                    </div>
                    <!--<div class="price">-->
                    <!--{{$t('goods.$')}}{{goods.price}}-->
                    <!--</div>-->
                    <div class="num">
                        {{goods.num}}
                    </div>
                    <!--<div class="price">-->
                    <!--{{$t('goods.$')}}{{goods.price*goods.num}}-->
                    <!--</div>-->
                </div>
            </div>
        </div>
        <el-steps :active="active">
            <el-step :title="$t('order.wait_package')" icon="iconfont icon-file"></el-step>
            <el-step :title="$t('order.transport')" icon="iconfont icon-fahuo"></el-step>
            <el-step :title="$t('order.dispatch')" icon="iconfont icon-paisongzhong"></el-step>
            <el-step :title="$t('order.received')" icon="iconfont icon-qianshou"></el-step>
        </el-steps>
        <div class="express-info">
            <span>{{$t('order.express_sn')}}:{{expressSn}}</span>
            <span>{{$t('order.express_name')}}:{{expressName}}</span>
        </div>
        <div class="data" v-loading="loading">
            <template v-if="data && data.length > 0">
                <ul>
                    <li v-for="item in data">
                        <span class="time">{{item.time}}</span>
                        <span class="context">{{item.context}}</span>
                    </li>
                </ul>
            </template>
            <template v-else>
                {{$t('common.no_data')}}
            </template>

        </div>

    </div>


</template>

<script>
    import api from "../api";
    import deliveryApi from "../api/delivery";
    import countryApi from "../api/country";

    export default {
        name: "express",
        data(){
            return {
                active:1,
                data:[],
                loading:false,
                deliveries: [],
                expressName:'',
                expressSn: '',
                countries: [],
                delivery: {},
            }
        },
        props:{
            orderId: ''
        },
        created(){
            this.getCountry();
        },
        watch:{
            orderId(value){
                this.getDeliveries();
                this.data = []
                this.deliveries = [];
                this.expressName = '';
                this.expressSn = '';
            }

        },
        methods:{
            getCountry(){
                countryApi.list( {all:1}).then(response => {
                    this.countries = response.data
                    this.getDeliveries();
                })
            },
            getExpress(index){

                if(this.deliveries.length > 0){
                    this.delivery = this.deliveries[index]
                    api.setPath('express/query');
                    const that = this;
                    this.loading = true;
                    const delivery = this.deliveries[index];
                    this.expressName = delivery.express_name;
                    this.expressSn = delivery.express_sn
                    api.list({com:delivery.express_code,post_id:delivery.express_sn}).then(response => {
                        if(response.status==="200"){
                            that.data                   =   response.data;
                            switch (response.state){
                                case '0':
                                    that.active         =   2;
                                    break;
                                case '5':
                                    that.active         =   3;
                                    break;
                                case '3':
                                    that.active         =   4;
                                    break;
                            }
                        }else {
                            that.data                   =   [];
                            that.active         =   1;
                        }
                        that.loading = false

                    })
                }

            },
            getDeliveries(){
                deliveryApi.list({ order_id: this.orderId, all: 1 }).then(response => {
                    var array = [];
                    response.data.forEach(function (item,index) {
                        if(index === 0){
                            item.type = 'success'

                        }else {
                            item.type = 'info'
                        }
                        array.push(item)
                    })
                    this.deliveries = array
                    this.getExpress(0);
                })
            },
            tagClick(index){
                const that = this
                this.deliveries.forEach(function (item,i) {
                    if(i===index){
                        item.type = 'success';
                        that.getExpress(index);
                    }else {
                        item.type = 'info'
                    }
                    that.$set(that.deliveries,i,item)
                })
            },
            countryText(id){
                var text = ''
                if(this.countries.length > 0){
                    this.countries.forEach(function (country) {
                        if(country.id === id){
                            text = country.name
                        }
                    })
                }
                return text;
            }
        }
    }
</script>

<style scoped lang="scss">
    .data{
        background-color: #f3f3f3;
        border-radius:5px;
        padding:15px  20px;
        ul{
            li{
                line-height: 2;
                padding:10px;
                .time{
                    display: inline-block;
                    width: 200px;
                }
            }
        }
    }
    .express-info{
        padding: 15px 20px;
        span{
            display: inline-block;
            width: 300px;
        }
    }
    .order-info {
        overflow: hidden;
        margin-top: 15px;
        background:#e8e8e8;
        padding:15px 0;
        .order-goods {
            float: left;
            .goods {
                text-align: center;
                overflow: hidden;
                .name, .price, .num, .after_service {
                    display: flex;
                    align-items: center;
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
                    width: 350px;
                    float: left;
                    overflow: hidden;
                    padding: 0 10px;
                    line-height: 25px;
                    span {
                        line-height: 25px;
                        height: 100px;
                        color: #999999;
                    }
                }
                .price, .num {
                    width: 100px;
                    float: left;
                    line-height: 25px;
                    color: #999999;
                }
                .after_service {
                    cursor: pointer;
                    line-height: 25px;
                    &:hover {
                        color: #35a0fc;
                    }
                }
            }
        }
    }

</style>