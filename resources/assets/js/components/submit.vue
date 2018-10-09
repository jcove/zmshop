<template>
    <div class="cart-submit-box container">
        <div class="box">
            <div class="address">
                <p class="text">
                    {{$t('cart.consignee_info')}}
                    <el-button type="primary" style="float: right;margin-top:-20px" @click="chooseAddressHandle">{{$t('operate.choose_address')}}</el-button>
                </p>
                <div class="info" v-if="address">
                    <div class="consignee">
                        {{address.consignee}}
                    </div>
                    <div class="region">
                        {{address.country}}{{address.province}}{{address.city}}{{address.disctrice}} {{address.address}}
                    </div>
                    <div class="phone">
                        {{address.phone}}
                    </div>
                </div>
                <div class="info" v-else>
                    <div class="add-address">
                        <el-button type="primary" @click="showAddressDialog">{{$t('operate.add_address')}}</el-button>
                    </div>


                </div>
            </div>
            <div class="goods-box">
                <p class="title">{{$t('cart.consignmen')}}</p>
                <div class="goods-list">
                    <template v-for="(group,gIndex) in cartGroup">

                        <div class="goods" v-for="(goods,index) in group.products">

                            <div class="cover float-left">
                                <img :src="goods.cover">
                            </div>
                            <div class="name float-left">
                                <a :href="getGoodsRoute(goods.id)">
                                    <p>{{goods.name}}</p>
                                </a>

                                <p v-if="goods.item">{{goods.item.name}}</p>
                            </div>
                            <div class="num float-left">
                                {{goods.num}}
                            </div>
                            <div class="price float-left">
                                {{$t('goods.$')}}{{goods.final_price}}
                            </div>

                            <div class="total float-left">
                                {{$t('goods.$')}}{{getTotal(goods.num,goods.final_price)}}
                            </div>

                        </div>

                    </template>
                </div>


            </div>
        </div>
        <!--<div class="pay-way box">-->
            <!--<template v-for="item in payments">-->
                <!--<el-radio v-model="form.pay_code" :label="item.pay_code">-->
                    <!--<img class="icon" :src="item.icon">-->
                <!--</el-radio>-->
            <!--</template>-->
        <!--</div>-->
        <div class="total-box">

            <div class="choose float-left">
                {{$t('cart.sub_num')}}<span class="num">{{chooseNum}}</span>{{$t('goods.unit')}}
            </div>
            <div class="total float-left">
                {{$t('region.shipping_fee')}}:{{$t('goods.$')}}{{shipping_fee}}
                {{$t('cart.owe')}}:<span class="amount">{{$t('goods.$')}}{{total}}</span>
            </div>
            <div class="settled float-right">
                <el-button type="danger" size="medium" @click="submit" style="height: 40px;background-color: #d01b0d">{{$t('operate.submit_order')}}

                </el-button>
            </div>
        </div>
        <el-dialog
                :title="$t('cart.add_address')"
                :visible.sync="dialogVisible"
                width="700px"
        >

            <div class="add-address-form">
                <el-form ref="form" :model="addressForm" label-width="80px" size="mini">

                    <el-form-item :label="$t('user.region')">
                        <el-select v-model="addressForm.country" placeholder="" style="width: 100px" @change="countryChange">
                            <el-option :label="country.name" :value="country.name" :key="country.id" v-for="country in countries">

                            </el-option>
                        </el-select>
                        <el-select v-model="addressForm.province" placeholder="" style="width:100px" :style="{display:regionShow}"
                                   @change="provinceChange">
                            <template v-for="item in province">
                                <el-option :label="item.name" :value="item.name"></el-option>
                            </template>

                        </el-select>
                        <el-select v-model="addressForm.city" placeholder="" style="width: 160px" :style="{display:regionShow}"
                                   @change="cityChange">
                            <template v-for="item in city">
                                <el-option :label="item.name" :value="item.name"></el-option>
                            </template>

                        </el-select>
                        <el-select v-model="addressForm.district" placeholder="" style="width: 160px"
                                   :style="{display:regionShow}">
                            <template v-for="item in district">
                                <el-option :label="item.name" :value="item.name"></el-option>
                            </template>
                        </el-select>

                    </el-form-item>
                    <el-form-item :label="$t('user.address')">
                        <el-input v-model="addressForm.address" style="width: 360px"></el-input>
                    </el-form-item>
                    <el-form-item :label="$t('user.consignee')">
                        <el-input v-model="addressForm.consignee" style="width: 160px"></el-input>
                    </el-form-item>
                    <el-form-item :label="$t('user.phone')">
                        <el-input v-model="addressForm.phone" style="width: 160px"></el-input>
                    </el-form-item>

                    <el-form-item size="large" label="" label-width="270px">
                        <el-button type="primary" @click="saveAddress">{{$t('common.save')}}</el-button>
                    </el-form-item>
                </el-form>
            </div>
        </el-dialog>
        <el-dialog :title="$t('operate.choose_address')" :visible.sync="addressListVisible">
            <el-table
                    :data="addresses"
                    border
                    style="width: 100%">
                <el-table-column
                        prop="consignee"
                        :label="$t('user.consignee')"
                        width="180">
                </el-table-column>
                <el-table-column
                        prop="region"
                        :label="$t('user.region')"
                        width="180">
                </el-table-column>
                <el-table-column
                        prop="address"
                        :label="$t('user.address')">
                </el-table-column>
                <el-table-column
                        prop="phone"
                        :label="$t('user.phone')">
                </el-table-column>
                <el-table-column
                        fixed="right"
                        :label="$t('common.operate')"
                        width="100">
                    <template slot-scope="scope">
                        <el-button @click="handleChoose(scope.$index,scope.row)" type="text" size="small">{{$t('operate.choose')}}</el-button>
                    </template>
                </el-table-column>
            </el-table>
            <div class="add-btn-box">
                <el-button type="primary" @click="showAddressDialog">{{$t('operate.add_address')}}</el-button>
            </div>

        </el-dialog>
        <el-dialog :visible="visible"  :title="$t('case_history.upload_case_history')">
            <el-form>
                <el-form-item :label="$t('common.name')" label-width="120px">
                    <el-input v-model="caseForm.name"></el-input>
                </el-form-item>
                <el-form-item :label="$t('common.gender')" label-width="120px">
                    <el-select v-model="caseForm.gender">
                        <el-option :value="1" :label="$t('common.man')">{{$t('common.man')}}</el-option>
                        <el-option :value="2" :label="$t('common.woman')">{{$t('common.woman')}}</el-option>
                    </el-select>
                </el-form-item>
                <el-form-item :label="$t('common.phone')" label-width="120px">
                    <el-input v-model="caseForm.phone"></el-input>
                </el-form-item>
                <el-form-item :label="$t('case_history.content')" label-width="120px">
                    <el-input v-model="caseForm.content" type="textarea" :span="6"></el-input>
                </el-form-item>
                <el-form-item :label="$t('case_history.case_history')" label-width="120px">
                    <el-upload class="file-upload"
                               :action="config.fileApi"
                               :file-list="files"
                               :on-success="fileHandleSuccess"
                               :headers="headers"
                               multiple
                               :on-remove="handleRemove">
                        <el-button size="small" type="primary">{{$t('operate.upload')}}</el-button>
                    </el-upload>
                </el-form-item>
                <el-form-item label-width="120px">
                    <el-button type="primary" @click="saveCaseHistory">{{$t('operate.submit')}}</el-button>
                </el-form-item>
            </el-form>
        </el-dialog>
    </div>
</template>

<script>
    import promotionApi from '../api/promotion';
    import cartApi from '../api/cart';
    import addressApi from "../api/address";
    import orderApi from '../api/order';
    import regionApi from '../api/region';
    import config from "../config";
    import paymentApi from "../api/payment";
    import caseHistoryApi from "../api/caseHistory";
    import goodsApi from "../api/goods";
    import countryApi from "../api/country";
    import freightApi from "../api/freight";
    export default {
        name: "submit",
        props: {
            carts: {
                type: Array,
                default: []
            }
        },
        data() {
            return {
                can: true,
                config:config,
                caseForm:{
                    name:'',
                    gender:1,
                    phone:'',
                    content:'',
                    files:[]
                },
                shipping_fee: 0,
                files:[],
                cartList: [],
                cartGroup: [],
                addresses: [],
                all: false,
                chooseNum: 0,
                total: 0,
                address:null,
                payments:[],
                countries:[],
                form:{
                    pay_code:'',
                    address_id:0
                },
                addressForm: {
                    consignee: '',
                    country: '',
                    province: '',
                    city: '',
                    district: '',
                    address: '',
                    phone: '',
                    is_default: 1
                },
                dialogVisible:false,
                regionShow: 'display',
                province: [],
                city: [],
                district: [],
                addressListVisible:false,
                visible: false,
                headers:{ 'X-Requested-With': 'XMLHttpRequest' },
            }
        },
        created() {
            this.cartList = this.carts;
            if(this.cartList.length ===0 ){
                location.href = config.baseApi + '/cart';
            }
            this.checkGoodsRx();
            this.getPrice();
            this.getDefaultAddress();
            this.getPayments();

        },
        watch:{
          'form.address_id':function (n,o) {
              this.getShippingFee();
          }
        },
        methods: {
            getTotal(num, price) {
                return Math.floor(price * num).toFixed(2);
            },
            checkGoodsRx(){
                var ids = ''
                this.cartList.forEach(function (goods) {
                    ids += goods.goods_id + ','
                })
                ids = ids.slice(0,ids.length-1);
                goodsApi.checkRx({id: ids}).then(response => {
                    if(response && response.message==='success'){
                        this.checkCaseHistory()
                    }
                })
            },
            checkCaseHistory(){
                const that              =   this;
                caseHistoryApi.check().then(response => {
                    if(response.message!=='success'){
                        that.visible        =   true;
                        that.can            =   false;
                    }
                });
            },
            saveCaseHistory(){
                const that              =   this;
                caseHistoryApi.save(this.caseForm).then(response => {
                    if(response){
                        that.can        =   true;
                        that.visible    =   false;
                    }
                })
            },
            fileHandleSuccess(response, file, fileList) {
                this.caseForm.files.push({ file_id: response.id, path: response.path });
                this.files = fileList
            },
            handleRemove(file, fileList) {
                this.files = fileList.slice(fileList.indexOf(file));
                this.caseForm.files = this.caseForm.files.slice(this.caseForm.files.indexOf({ file_id: file.response.id, path: file.response.path }))
            },
            getPrice() {
                var products = [];
                this.carts.forEach(function (cart) {
                    const product = {
                        id: cart.goods_id,
                        name: cart.goods_name,
                        price: cart.price,
                        num: cart.num,
                        cover: cart.cover,
                        is_check: cart.is_check === 1,
                        cart_id:cart.id
                    };
                    products.push(product);
                });
                const that = this;
                promotionApi.products({products: products}).then(response => {
                    that.cartGroup = response;
                    that.computeTotal();
                    console.log(this.total)
                })
            },
            handleNumChange(value) {

            },
            handelCartCheckChange(goods) {
                this.computeTotal();
                cartApi.check(goods.cart_id).then();
            },
            deleteCart(goods, gIndex, index) {

            },
            computeTotal(){
                if (this.cartGroup.length > 0) {
                    var total = 0;
                    this.chooseNum =0;
                    const that =this;
                    this.cartGroup.forEach(function (group) {
                        if (group.products.length > 0) {

                            group.products.forEach(function (goods) {
                                if(goods.is_check){
                                    total +=goods.num*goods.final_price;
                                    that.chooseNum+=goods.num;
                                }

                            });
                        }
                    })
                    total += this.shipping_fee;
                    this.total = Math.floor(total).toFixed(2);
                }
            },
            getCountry(){
                countryApi.list( {all:1}).then(response => {
                    this.countries = response.data
                })
            },
            getDefaultAddress(){
                const that = this;
                addressApi.default().then(response => {
                    if(response){
                        that.address = response.data;
                        that.form.address_id = response.data.id
                    }

                });
            },
            submit(){
                if(!this.can){
                    this.visible = true
                    return
                }
                const that                  =   this;
                orderApi.createOrder(this.form).then(response=>{
                    if(response){
                        // that.$message({
                        //     message: that.$t('cart.order_submit_success'),
                        //     type: 'success'
                        // });
                        // orderApi.pay(response.data.id).then(response => {
                        //     if(response.pay_url){
                        //         location.href           =   response.pay_url;
                        //     }
                        // })
                        location.href = config.baseApi+'/order/success/'+response.data.id;
                    }


                })
            },
            getGoodsRoute(id){
                return config.baseApi+'/goods/'+id;
            },
            getShippingFee(){
              freightApi.shippingFee({address_id:this.form.address_id}).then(response => {
                  this.shipping_fee = response.fee;
                  this.computeTotal();
                  console.log(this.total)
              })
            },
            countryChange(value) {
                if (value === this.countries[0].name) {
                    this.regionShow= 'inline-block';
                    const that = this;
                    regionApi.children(0).then(response => {
                        that.province = response.data;
                    });
                }else {
                    this.regionShow= 'none';
                }
            },
            provinceChange(value) {
                const that = this;
                regionApi.children(value).then(response => {
                    that.city = response.data;
                });

            },
            cityChange(value) {
                const that = this;
                regionApi.children(value).then(response => {
                    that.district = response.data;
                });

            },
            showAddressDialog(){
                this.dialogVisible          =   true;
                this.addressListVisible             =   false;
                this.getCountry();
            },
            saveAddress() {
                const that = this;
                addressApi.save(this.addressForm).then(response => {
                    if(response){
                        this.$message({
                            message: that.$t('common.success'),
                            type: 'success'
                        });
                        that.address=response.data;
                        that.form.address_id = response.data.id;
                        that.dialogVisible          =   false;
                    }
                })
            },
            handleChoose(index,item){
               this.address                         =   item;
               this.form.address_id                 =   item.id;
               this.addressListVisible              =   false;
            },
            chooseAddressHandle(){
                this.addressListVisible             =   true;
                const that                          =   this;
                addressApi.list().then(response =>{
                   if(response){
                       that.addresses               =   response.data;
                       that.addresses.forEach(function (item) {
                           const index              =   that.addresses.indexOf(item);
                           item.region              =   item.country+item.province+item.city+item.district;
                           that.$set(that.addresses,index,item);
                       })
                   }
                });

            },
            getPayments(){
                const that = this
                paymentApi.list().then(response => {
                    that.payments = response.data
                })
            }
        }
    }
</script>

<style scoped lang="scss">
    .cart-submit-box{
        .box{
            border:1px solid #eaeaea;
            .address{
                margin:20px;
                border-bottom:1px solid #eaeaea;

                .text{
                    padding: 10px 0;
                    border-bottom:1px solid #eaeaea;
                }
                .info{
                    overflow: hidden;
                    padding: 20px 0;
                    .consignee{
                        width: 180px;
                        float: left;
                        padding: 15px;

                    }
                    .region{
                        width: 300px;
                        float: left;
                        padding: 15px 0;
                    }
                    .phone{
                        width: 180px;
                        float: left;
                        padding: 15px 0;
                    }

                    .add-address{
                        text-align: center;
                    }
                }
            }
        }
    }
    .goods-box{
        .title{
            padding: 10px 20px;
        }
        .goods-list{
            border:1px solid #35a0fc;
            margin: 20px;
            .goods {
                overflow: hidden;
                height: 120px;
                border-bottom: 1px solid #e8e8e8;
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
                        text-align: center;
                        line-height: 40px;
                    }
                }
                .num, .price {
                    width: 140px;
                    line-height: 120px;
                    text-align: center;
                }
                .total {
                    width: 140px;
                    line-height: 120px;
                    text-align: center;
                }
                .tools {
                    width: 120px;
                    text-align: center;
                    line-height: 120px;
                }
            }
        }
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
        padding: 15px;
        background-color: #e2e2e2;
        margin-top: 20px;
        text-align: center;
        .choose {
            width: 140px;
            margin-left: 600px;
            .num{
                color: #d01b0d;
            }
        }
        .total {
            width: 240px;
            .amount{
                color: #d01b0d;
            }
        }
        .settled {
            margin-top: -5px;
            margin-right: -5px;
        }
    }
    .pay-way{
        padding: 15px 30px;
        .icon{
            width:90px;
            height:40px;
        }
    }
    .add-btn-box{
        padding: 15px;
        text-align: center;
    }

</style>