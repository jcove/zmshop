<template>
    <div class="cart-box container">
        <template v-if="carts.length > 0">
            <div class="title-box">
                <div class="check float-left">
                    <el-checkbox v-model="checkAll" :label="$t('operate.check_all')"  @change="handleCheckAllChange"></el-checkbox>
                </div>
                <div class="name float-left">
                    <p>{{$t('cart.goods_info')}}</p>
                </div>
                <div class="price float-left">
                    {{$t('cart.goods_price')}}
                </div>
                <div class="num float-left">
                    {{$t('goods.number')}}
                </div>
                <div class="total float-left">
                    {{$t('cart.goods_total')}}
                </div>
                <div class="tools float-left">
                    {{$t('cart.operate')}}
                </div>
            </div>
            <div class="goods-box">

                <template v-for="(goods,index) in cartList">

                    <div class="goods cart-disabled" v-if="goods.status !== 1" >

                        <div class="check float-left">
                            {{$t('cart.invalid')}}
                        </div>
                        <div class="cover float-left">
                           <a  :href="'goods/'+goods.goods_id" target="_blank">
                               <img :src="goods.cover">
                           </a>
                        </div>
                        <div class="name float-left">
                            <a  :href="'goods/'+goods.goods_id" target="_blank">
                                <p>{{goods.goods_name}}
                                <template v-if="goods.goods_spec_item_name">
                                    <span style="color: #35A0FC">{{goods.goods_spec_item_name}}</span>
                                </template>
                                </p>
                            </a>
                        </div>
                        <div class="price float-left">
                            {{goods.price}}
                        </div>
                        <div class="num float-left">
                            <el-input-number v-model="goods.num" disabled @change="handleNumChange" :min="1" :max="1000"
                                             size="mini"></el-input-number>
                        </div>
                        <div class="total float-left">
                            {{getTotal(goods.num,goods.price)}}
                        </div>
                        <div class="tools float-left">
                            <a href="javascript:void(0)"
                               @click="deleteCart(goods,gIndex,index)">{{$t('operate.delete')}}</a>
                        </div>
                    </div>

                </template>

            </div>
            <div class="goods-box">

                <template v-for="(group,gIndex) in cartGroup">

                    <div class="goods" v-for="(goods,index) in group.products">

                        <div class="check float-left">
                            <el-checkbox v-model="goods.is_check" @change="handelCartCheckChange(goods)"></el-checkbox>
                        </div>
                        <div class="cover float-left">
                            <a  :href="'goods/'+goods.id" target="_blank">
                                <img :src="goods.cover">
                            </a>
                        </div>
                        <div class="name float-left">
                            <a  :href="'goods/'+goods.id" target="_blank">
                            <p>{{goods.name}}
                            <template v-if="goods.goods_spec_item_name">
                                <span style="color: #35A0FC">[{{goods.goods_spec_item_name}}]</span>
                            </template>

                            </p>
                            </a>
                        </div>
                        <div class="price float-left">
                            <p>{{(new Number(goods.price)).toFixed(2)}}</p>
                            <p class="promotion" v-if="group.id > 0">{{$t('cart.promotion')}}:<span class="promotion-name">{{group.name}}</span></p>
                        </div>
                        <div class="num float-left">
                            <el-input-number v-model="goods.num" @change="handleNumChange(goods,gIndex,index)" :min="1" :max="goods.max"
                                             size="mini"></el-input-number>
                        </div>
                        <div class="total float-left">
                            {{getTotal(goods.num,goods.price)}}
                        </div>
                        <div class="tools float-left">
                            <a href="javascript:void(0)"
                               @click="deleteCart(goods,gIndex,index)">{{$t('operate.delete')}}</a>
                        </div>
                    </div>

                </template>

            </div>
            <div class="total-box">
                <a href="javascript:void(0)" class="delete-cart float-left" @click="deleteChecked">
                    {{$t('operate.delete_checked')}}
                </a>
                <a href="javascript:void(0)" class="collect float-left" @click="removeToCollection">
                    {{$t('operate.collect')}}
                </a>
                <div class="choose float-left">
                    {{$t('cart.choose_num')}}:{{chooseNum}}
                </div>
                <div class="total float-left">
                    {{$t('cart.total')}}:<span class="amount">{{$t('goods.$')}}{{total}}</span>
                    {{$t('cart.saved')}}{{$t('goods.$')}}{{promotion}}
                </div>
                <div class="settled float-right">
                    <el-button type="primary" size="medium" @click="submit">{{$t('operate.settled')}}

                    </el-button>
                </div>

            </div>
        </template>
        <template v-else>
            <div class="cart-empty">
                <span class="iconfont icon-cart"></span>
                <p>{{$t('cart.empty')}}<a :href="config.baseApi">{{$t('common.index')}}</a></p>
            </div>
        </template>

    </div>
</template>

<script>
    import promotionApi from '../api/promotion';
    import cartApi from '../api/cart';
    import collectionApi from "../api/collection";
    import config from "../config";
    export default {
        name: "cart",
        props: {
            carts: {
                type: Array,
                default: []
            },

        },
        data() {
            return {
                cartList: [],
                cartGroup: [],
                all: false,
                chooseNum: 0,
                total: 0,
                config:config,
                count: 0,
                checkAll:false,
                promotion: 0

            }
        },
        created() {
            this.cartList = this.carts;
            this.count = this.carts.length
            this.getPrice();

        },
        methods: {
            getTotal(num, price) {
                return (new Number(price * num)).toFixed(2);
            },
            getPrice() {
                var products = [];
                const that = this;
                var count           =   0;
                this.carts.forEach(function (cart) {
                    const product = {
                        id: cart.goods_id,
                        name: cart.goods_name,
                        price: cart.price,
                        num: cart.num,
                        cover: cart.cover,
                        is_check: cart.is_check === 1,
                        cart_id:cart.id,
                        goods_spec_item_name:cart.goods_spec_item_name,
                        max:cart.store
                    };
                    if(cart.is_check ===1 ){
                        count++;
                    }
                    products.push(product);
                });
                this.checkAll   =   this.count === count;
                promotionApi.products({products: products}).then(response => {
                    that.cartGroup = response;
                    that.computeTotal();
                })
            },
            handleNumChange(goods,gIndex,index) {
                this.cartGroup[gIndex].products[index].disabled = true
                cartApi.save({id:goods.cart_id,num:goods.num}).then(response => {
                    if(response){
                        this.cartGroup[gIndex].products[index].disabled = false
                    }
                },error => {
                });
                const that = this
                this.carts.forEach(function (cart,index) {
                    if(cart.id === goods.cart_id){
                        cart.num = goods.num
                    }
                    that.$set(that.carts,index,cart)

                })
                this.getPrice();
            },
            handelCartCheckChange(goods) {
                const that = this
                this.carts.forEach(function (cart,index) {
                    if(cart.id === goods.cart_id){
                        cart.is_check = goods.is_check  ? 1:0
                    }
                    that.$set(that.carts,index,cart)

                })
                this.getPrice();
                cartApi.check(goods.cart_id).then();
            },
            deleteCart(goods, gIndex, index) {
                const that              =   this;
                cartApi.delete(goods.cart_id).then(response=>{
                    if(response){
                        that.$message({
                            message:that.$t('common.success'),
                            type:'success',
                            showClose:true
                        })
                        that.cartGroup[gIndex].products.splice(index,1)
                        location.reload()
                    }
                })
            },
            deleteChecked(){
                const that              =   this;
                cartApi.deleteChecked().then(response => {{
                    if(response){
                        that.$message({
                            message:that.$t('common.success'),
                            type:'success'
                        })
                        that.removeChecked();
                    }
                }});
            },
            removeToCollection(){
                const that              =   this;
                collectionApi.cartTo().then(response =>{
                    if(response){
                        that.$message({
                            message:that.$t('common.success'),
                            type:'success'
                        });
                        that.removeChecked();
                    }
                });
            },
            removeChecked(){
                const that                      =   this;
                this.cartGroup.forEach(function (group) {
                    const gIndex                =   that.cartGroup.indexOf(group);
                    group.products.forEach(function (product) {
                        const index             =   group.products.indexOf(product);
                        if(product.is_check){
                            group.products.splice(index,1);
                        }
                    });

                    that.cartGroup[gIndex]  =   group;

                })
            },
            computeTotal(){
                if (this.cartGroup.length > 0) {
                    var total = 0;
                    var count = 0;
                    var promotion = 0
                    this.cartGroup.forEach(function (group) {
                        if (group.products.length > 0) {

                            group.products.forEach(function (goods) {
                                if(goods.is_check){
                                    total +=goods.num*goods.final_price;
                                    count++;
                                }

                            });
                        }
                        promotion+= group.promotion
                    })
                    this.promotion = promotion;
                    this.total = (new Number(total)).toFixed(2);
                    this.chooseNum = count
                }
            },
            submit(){
                if(this.hasCheckd()){
                    location.href="/cart/submit";
                }else {
                    this.$message({
                        message:this.$t('cart.please_choose_goods'),
                        type:'success'
                    });
                }

            },
            hasCheckd(){
                var check = false
                this.cartGroup.forEach(function (group) {
                    if(group.products.length > 0){
                        group.products.forEach(function(goods){
                            if(goods.is_check) {
                                check = true
                            }
                        })
                    }
                })
                return check;
            },
            handleCheckAllChange(value) {
                const that               =   this;
                cartApi.checkAll({is_check:value ? 1: 0}).then(response => {
                    this.cartGroup.forEach(function (group,i) {
                        group.products.forEach(function (product,index) {
                            product.is_check = value;
                            that.$set(group.products,index,product);
                        });
                        that.$set(that.cartGroup,i,group);
                    })
                });

            }
        }
    }
</script>

<style scoped lang="scss">
    .goods {
        overflow: hidden;
        height: 120px;
        border: 1px solid #e8e8e8;
        margin-top: 5px;
        padding: 25px;
        .check {
            width: 50px;
            float: left;
            line-height: 120px;
        }
        .cover {
            width: 160px;
            float: left;
            img {
                width: 120px;
                height: 120px;
                display: block;
                margin: auto;
            }
        }
        .name {
            width: 300px;
            float: left;
            p {
                /*text-align: center;*/
                /*line-height: 120px;*/
            }
        }
        .price{
            .promotion{
                .promotion-name{
                    border:1px solid red;
                    font-size: 11px;
                    color:red;
                }
            }
        }
        .num, .price {
            width: 140px;
            /*line-height: 120px;*/
            text-align: center;
        }
        .total {
            width: 140px;
            /*line-height: 120px;*/
            text-align: center;
        }
        .tools {
            width: 120px;
            text-align: center;
            /*line-height: 120px;*/
        }
    }
    .cart-disabled{
        background-color: #f0f0f0;
    }
    .title-box {
        overflow: hidden;
        height: 30px;
        border: 1px solid #e8e8e8;

        padding: 5px;
        .check {
            width: 50px;
            float: left;
            line-height: 30px;
        }

        .name {
            width: 480px;
            float: left;
            p {
                text-align: center;
                line-height: 30px;
            }
        }
        .num, .price {
            width: 140px;
            line-height: 30px;
            text-align: center;
        }
        .total {
            width: 140px;
            line-height: 30px;
            text-align: center;
        }
        .tools {
            width: 30px;
            text-align: center;
            line-height: 30px;
        }

    }

    .total-box {
        height: 30px;
        line-height: 30px;
        padding: 5px;
        border: 1px solid #e8e8e8;
        text-align: center;
        .delete-cart {
            width: 300px;
        }
        .collect {
            width: 200px;
        }
        .choose {
            width: 140px;
        }
        .total {
            width: 340px;
            .amount{
                color: #ffa800;
            }
        }
        .settled {
            margin-top: -5px;
            margin-right: -5px;
        }
    }
    .cart-empty{
        text-align: center;
        padding: 100px 20px;
        .iconfont{
            color: #f0ad4e;
            font-size: 140px;
        }
        p{
            font-size: 13px;
            text-align: center;
            color: #999999;
            a{
                color: #35a0fc;
                padding: 0 10px;
            }
        }
    }
</style>