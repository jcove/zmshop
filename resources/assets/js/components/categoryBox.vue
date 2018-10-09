<template>

    <div class="index-category-box container" :id="category.id">
        <div class="adp-banner">
            <a :href="adBanner.link">
            <img :src="adBanner.code"/>
            </a>
        </div>
        <div class="category-children" v-loading="categoryLoading">
            <div class="title" :style="{background: backgrounds[index]}">
                {{category.name}}{{index+1}}F
            </div>
            <ul>
                <template v-if="categories">
                    <template  v-for="(item ,index) in categories">
                        <li v-if="index < 10">

                            <a :href="getCategoryRoute(item.id)">
                                {{item.name}}
                            </a>

                        </li>
                    </template>
                </template>

                <template v-if="brands">
                    <template v-for="(item ,index) in brands">
                        <li v-if="index < 10" >
                            <a :href="'/brand/'+item.id">
                                <img :src="item.logo">
                            </a>
                        </li>
                    </template>
                </template>


            </ul>
        </div>
        <div class="goods-box" :style="{borderColor: colors[index]}">
            <div class="adp-goods float-left">
                <div class="adp float-left">
                    <category-slider :id="category.id"></category-slider>
                </div>
            </div>
            <div class="goods" v-loading="goodsLoading">
                <ul >
                    <template  v-for="(item,index) in goods">
                        <li v-if="index < 8">
                            <a :href="'goods/'+item.id">
                                <div class="cover">
                                    <img class="img-responsive" :src="item.cover"/>
                                </div>
                                <p class="name">{{item.name}}</p>
                                <p class="price">{{item.price}}</p>
                            </a>
                        </li>
                    </template>

                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    import goodsCategoryApi from '../api/goodsCategory';
    import goodsApi from '../api/goods';
    import adApi from '../api/ad';
    import brandApi from '../api/brand';
    import config from "../config";
    export default {
        name: 'category-box',
        props:{
            index:0,
            color:{
                type: String,
                default() {
                    return 'red'
                }
            },
            category:Object
        },
        data(){
            const that = this;
            return {
                categories:[],
                goods:[],
                goodsGroup:[],
                brands:[],
                adBanner:[],
                adCover:'',
                categoryLoading:true,
                goodsLoading:true,
                adLoading:true,
                colors:[
                    '#ff462d',
                    '#ffa92b',
                    '#925fbe',
                    '#90b821',
                    '#38adfd'

                ],
                backgrounds:[
                    'url("/images/category_box_bg_1.png")' ,
                    'url("/images/category_box_bg_2.png")',
                    'url("/images/category_box_bg_3.png")',
                    'url("/images/category_box_bg_4.png")',
                    'url("/images/category_box_bg_3.png")'
                ],

                query:{
                    is_recommend:1,
                    category_id:that.category.id
                }


            }
        },
        created(){
            this.getCategoryChildren();
            this.getGoods(0);
            this.getBrand();
            this.getAd();
        },
        methods:{
            getCategoryChildren(){
                if(this.category){
                    const that = this;
                    this.categoryLoading = true;
                    goodsCategoryApi.children(this.category.id).then(response=>{
                        that.categoryLoading    =   false;
                        that.categories         =   response;
                    });
                }
            },
            getGoods(index){
                const that = this;
                this.goodsLoading = true;
                goodsApi.list(this.query).then(response=>{
                    that.goodsLoading       =   false;
                    that.goods              =   response.data;
                });
            },
            getBrand(){
                if(this.category){
                    const that = this;
                    brandApi.list({category_id:this.category.id}).then(response=>{
                        that.brands              =   response.data;
                    });
                }
            },
            getAd(){
                if(this.category){
                    const that = this;
                    this.adLoading = true;
                    adApi.list({position:'pc_index_category_banner_'+this.category.id}).then(response=>{
                        if(response.data.length > 0){
                            that.adBanner = response.data[0];
                        }
                    });
                }
            },
            getCategoryRoute(id){
                return config.baseApi+'/goods?category_id='+id;
            }
        }

    }
</script>

<style scoped lang="scss">
    @import "../../sass/variables";
    .index-category-box{
        overflow: hidden;
        .adp-banner{
            width: 1140px;
            height: 90px;
            margin-top: 20px;
            margin-bottom: 20px;
            img{
                width: 1140px;
                height: 66px;
            }
        }
    }

    .index-category-box .category-children{
        height: 435px;
        float: left;
        width:200px;
        .title{
            float: left;
            font-size: 24px;
            width: 200px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            color: white;
            background-color: $navbar-default-bg;
        }
    }
    .index-category-box .category-children ul{
        width: 200px;
        overflow: hidden;
    }
    .index-category-box .category-children li{
        width: 98px;
        float: left;
        border:1px solid #e8e8e8;
        border-top:0;
        text-align:center;
        line-height: 40px;
        font-size: 14px;
        font-weight: 600;

    }
    .index-category-box .category-children li img{
        height: 40px;
        width: 90px;
        display: block;
        margin: auto;
    }
    .index-category-box .goods-box {
        width: 940px;
        float: left;
        height: 450px;
        border-top-width: 2px;
        border-top-style: solid;
        .adp-goods{
            width:340px;
            border-right: 1px solid #e8e8e8;
            border-bottom: 1px solid #e8e8e8;
            .adp{
                height: 447px;
                width: 340px;
                img{
                    width: 340px;
                    height:447px;
                }
            }
        }
        .goods{
            width:596px;
            height:448px;
            float:left;
            border-left: 1px solid #e8e8e8;
            margin-left: 2px;
            li{
                width: 148px;
                float:left;
                height:223px;
                overflow:hidden;
                border-bottom: 1px solid #e8e8e8;
                border-right: 1px solid #e8e8e8;
                p{
                    text-align:center;

                }
                .cover{
                    width: 148px;
                    height: 140px;
                    overflow:hidden;
                    img{
                        transition: all 0.4s ease-out 0s;
                        -ms-transition: all 0.4s ease-out 0s;
                        -webkit-transition: all 0.4s ease-out 0s;
                        width: 140px;
                        height: 140px;
                    }
                }
                .price{
                    color:#fb4d72;
                    margin-top: 10px;
                    font-size: 16px;
                }
                &:hover{
                    img{
                        transform:scale(1.03);
                        -ms-transform:scale(1.03);
                        -webkit-transform:scale(1.03);
                    }
                    .name{
                        color: red;
                    }
                }
            }
        }

    }
</style>