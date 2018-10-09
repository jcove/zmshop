<template>
    <div class="price-box">
        <p>
            <span class="price-lang">
                {{$t('goods.shop_price')}}:
            </span>
            <span class="price">
                ￥{{price}}
            </span>
        </p>
        <template v-if="promotions">
            <p v-for="promotion in promotions">
            <span class="price-lang">
                {{$t('goods.promotion')}}:
            </span>
                <span class="promotion-name">
                ￥{{promotion.name}}
            </span>
            </p>
        </template>

    </div>
</template>

<script>
    import promotionApi from "../api/promotion";

    export default {
        name: "goods-price",
        data(){
            return {
                promotions: null
            }

        },
        props:{
            price:0,
            id: 0,
            name: ''
        },
        created(){
            this.getPromotion()
        },
        methods:{
            getPromotion(){
                const product = { id:id,name:name,price:price };
                promotionApi.product({ product: product}).then(response => {
                   if(response){
                        this.promotions = response
                   }
                });
            }
        }
    }
</script>

<style scoped lang="scss">
    .price-box{
        background-color: #f2f2f2;
        padding: 15px 10px;
        width:350px;
        .price{
            color:#ff4f5e;
            font-size:16px;
        }
        .promotion-name{
            border: #ff4f5e;
        }
    }
</style>