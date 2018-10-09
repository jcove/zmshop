<template>
    <div class="info">
        <h2 class="name">
            {{goods.name}}
        </h2>
        <div class="price-box">
            <p>
            <span class="price-lang">
                {{$t('goods.shop_price')}}:
            </span>
                <span class="price">
                ￥{{goods.price}}
            </span>
            </p>
            <p v-if="region">
                <span class="price-lang">
                {{$t('region.region')}}:
            </span>
                <span class="region" @click="setRegion()">{{region.country.name}}
                    <template v-if="region.region">
                         -{{region.region.name}}<i class="iconfont icon-down"></i>
                    </template>

            </span>
                <span style="margin-left: 10px">{{shipping_fee}}</span>

            </p>
            <template v-if="promotions">
                <p v-for="promotion in promotions">
            <span class="price-lang">
                {{$t('goods.promotion')}}:
            </span>
                    <span class="promotion-name">{{promotion.name}}
            </span>
                </p>
            </template>
        </div>
        <p class="brand info-item" v-if="goods.brand">
            <span>{{$t('goods.brand')}}:</span>
            <span>{{goods.brand.name}}</span>
        </p>
        <p class="brand info-item" v-for="attr in goods.attrs">
            <span>{{attr.attribute_name}}:</span>
            <span>{{attr.attribute_value}}</span>
        </p>
        <div class="" v-if="goods.specifications.length > 0">

            <template v-for="(specification,index) in goods.specifications">
                <div class="specification info-item">
                    <span class="name">{{specification.name}}:</span>
                    <el-radio-group v-model="spec[index].value" @change="change">
                        <template v-for="item in specification.items">
                            <el-radio-button :label="item.id">{{item.value}}</el-radio-button>
                        </template>

                    </el-radio-group>
                </div>
            </template>
        </div>
        <p class="info-item">
            <span>{{$t('goods.number')}}</span>
            <el-input v-model="form.num" type="number" style="width:100px"></el-input>
            <el-button type="primary" @click="addCart()" :disabled="!this.can">{{$t('goods.add_cart')}}</el-button>
        </p>
        <el-dialog
                :title="$t('region.region')"
                :visible.sync="dialogVisible"
                width="700px">
            <div class="add-address-form">
                <el-form  label-width="80px" size="mini">
                    <el-form-item :label="$t('user.region')">
                        <el-select  placeholder="" v-model="temp.country_id" style="width: 100px" @change="countryChange">
                            <el-option :label="country.name" :value="country.id" :key="country.id" v-for="country in countries">

                            </el-option>

                        </el-select>
                        <el-select placeholder="" v-model="temp.province_id" style="width:100px" :style="{display:regionShow}"
                                   @change="provinceChange">
                            <template v-for="item in province">
                                <el-option :label="item.name" :value="item.id"></el-option>
                            </template>

                        </el-select>
                        <el-select  placeholder="" v-model="temp.region_id" style="width: 160px" :style="{display:regionShow}"
                                   @change="cityChange">
                            <template v-for="item in city">
                                <el-option :label="item.name" :value="item.id"></el-option>
                            </template>

                        </el-select>

                    </el-form-item>
                    <el-form-item size="large" label="" label-width="270px">
                        <el-button type="primary" @click="confirm">{{$t('common.confirm')}}</el-button>
                    </el-form-item>
                </el-form>
            </div>
        </el-dialog>

    </div>
</template>

<script>
    import cartApi from '../api/cart';
    import promotionApi from "../api/promotion";
    import freightApi from "../api/freight";
    import addressApi from "../api/address";
    import countryApi from "../api/country";
    import regionApi from "../api/region";
    export default {
        name: "goods-info",
        props: {
            info: Object
        },
        data() {
            return {
                form: {
                    num: 1,
                    item_id: 0,
                    goods_id: 0
                },
                shipping_fee: '',
                spec: [],
                goods: '',
                can: true,
                error: '',
                files: [],
                visible: false,
                promotions: null,
                region: {
                    country:{
                        id:1,
                        name:'中国'
                    },
                    region:{
                        id:1,
                        name:'北京'
                    }
                },
                countries: [],
                province: [],
                city: [],
                dialogVisible: false,
                regionShow: 'none',
                temp: {
                    region_id: '',
                    country_id: '',
                    province_id: ''
                },


            }
        }
        , created() {
            this.goods = this.info;
            this.form.goods_id = this.goods.id;
            if (this.goods.specifications.length > 0) {
                const that = this;
                var i = 0;
                this.goods.specifications.forEach(function (item) {
                    that.$set(that.spec, i, {name: item.name, value: ''});
                    i++
                });
            }
            this.getPromotion();
            this.getUserRegion();

        },
        watch:{
          region(n,o){
              console.log(n)
              this.getFreight()
          }
        },
        methods: {
            setRegion(){
              this.dialogVisible =true;
              this.getCountry();

            },
            change(value) {
                this.initPrice();
            },
            addCart() {
                if (!this.can) {
                    this.$message.error({
                        message: this.error,
                    });
                    return
                }

                this.doAddCart();

            },
            doAddCart() {
                cartApi.addCart(this.form).then(response => {
                    const that = this;
                    this.$message({
                        message: this.$t('cart.add_success'),
                        type: 'success'
                    });
                });
            },
            initPrice() {
                if (this.spec.length > 0) {
                    const items = [];
                    var flag = false;
                    this.spec.forEach(function (item) {
                        if (item.value === '') {
                            flag = true;
                            return;
                        }
                        items.push(item.value);
                    });
                    if (flag) {
                        return
                    }
                    const key = items.sort(function (a, b) {
                        return a - b;
                    }).join('_');
                    this.goods.price = this.goods.specification_prices[key].price;
                    Vue.set(this.goods, 'price', this.goods.specification_prices[key].price);
                    this.form.item_id = this.goods.specification_prices[key].item_id;
                    if (this.form.num > this.goods.specification_prices[key].store) {

                        this.can = false;
                        this.error = this.$t('goods.store_out');
                        this.$message.error({
                            message: this.error,
                        });
                        return;
                    }

                }

            },
            getFreight() {
                freightApi.fee({goods_id: this.goods.id,region_id:this.region.region.id,country_id:this.region.country.id}).then(response =>{
                    if(response.code && response.code > 0){
                        this.can = false;
                        this.shipping_fee = this.$t('region.can_not_shipping');
                        this.error = this.$t('region.can_not_shipping');
                    }else {
                        this.shipping_fee = this.$t('region.shipping_fee')+':'+this.$t('goods.$')+response.fee;
                        this.can = true;
                    }
                } );
            },
            getUserRegion() {
                addressApi.region().then(response => {
                    if (response) {
                        this.region = response
                        this.getFreight()
                    }
                })
            },
            getPromotion() {
                const product = {id: this.goods.id, name: this.goods.name, price: this.goods.price};
                promotionApi.product({product: product}).then(response => {
                    if (response) {
                        this.promotions = response
                    }
                });
            },
            getCountry(){
                countryApi.list( {all:1}).then(response => {
                    this.countries = response.data
                })
            },
            countryChange(value) {
                const that = this;
                this.countries.forEach(function (item) {
                    if(item.id ===value){
                        that.region.country = item
                        console.log(that.region.country)
                    }
                })
                if (value === 1) {
                    this.regionShow= 'inline-block';

                    regionApi.children(0).then(response => {
                        that.province = response.data;
                    });
                }else {
                    this.regionShow= 'none';
                }
                this.getFreight()
            },
            provinceChange(value) {
                const that = this;
                regionApi.children(value).then(response => {
                    that.city = response.data;
                });

            },
            cityChange(value){
                const that = this;
                this.city.forEach(function (item) {
                    if(item.id ===value){
                        that.region.region = item
                    }
                })
                this.getFreight()
            },
            confirm(){
                this.dialogVisible = false
            }
        }
    }
</script>

<style scoped lang="scss">
    .price-box {
        background-color: #f2f2f2;
        padding: 5px 10px;
        width: 350px;
        p {
            padding: 10px 0;
        }
        .price {
            color: #ff4f5e;
            font-size: 16px;
        }
        .promotion-name {
            border: 1px solid #ff4f5e;
            color: #ff4f5e;
            font-size: 12px;
            padding: 2px 5px;
        }
        .region{
            border: 1px solid #e8e8e8;
            padding: 5px;
            background-color: white;
            cursor: pointer;
        }
    }
</style>