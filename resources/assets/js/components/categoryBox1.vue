<template>

    <div class="index-category-box container" :id="category.id">
        <div class="adp-banner">
            <a :href="adBanner.link">
            <img :src="adBanner.code"/>
            </a>
        </div>
        <div class="category-title" :style="{borderColor: colors[index]}">
            <div class="title" :style="{background: colors[index]}">
                {{category.name}}{{index+1}}F
            </div>
            <ul>
                <li :class="{active:filters[0].active}" @mouseenter ="mouseOver(0)">
                    {{ $t('index.category_box.recommend') }}
                </li>
                <li  :class="{active:filters[1].active}" @mouseenter ="mouseOver(1)">
                    {{ $t('index.category_box.hot') }}
                </li>
                <li  :class="{active:filters[2].active}" @mouseenter ="mouseOver(2)">
                    {{ $t('index.category_box.special') }}
                </li>
            </ul>
        </div>
        <div class="category-children" v-loading="categoryLoading">
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
        <div class="goods-box">
            <div class="adp-goods float-left">
                <div class="adp float-left" v-loading="adLoading">
                    <a :href="adCover.link">
                        <img :src="adCover.code"/>
                    </a>

                </div>
                <div class="adp-b-goods float-left " v-loading="goodsLoading">
                    <ul >
                        <template  v-for="(item,index) in goods">
                            <li v-if="index < 4">
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
            <div class="goods" v-loading="goodsLoading">
                <ul >
                    <template  v-for="(item,index) in goods">
                        <li v-if="index < 8 && index > 3">
                            <a :href="'goods/'+item.id">
                                <div class="cover">
                                    <img class="img-responsive" :src="item.cover"/>
                                </div>
                                <p>{{item.name}}</p>
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
                adBanner:'',
                adCover:'',
                categoryLoading:true,
                goodsLoading:true,
                adLoading:true,
                colors:[
                    '#e83226',
                    '#ffa92b',
                    '#38adfd',
                    '#90b821',
                    '#925fbe'
                ],
                filters:[
                    {
                        active: true,
                        query:{
                            is_recommend:1,
                            category_id:that.category.id
                        }
                    },
                    {
                        active: false,
                        query:{
                            is_hot:1,
                            category_id:that.category.id
                        }
                    },
                    {
                        active: false,
                        query:{
                            is_special:1,
                            category_id:that.category.id
                        }
                    }
                ]
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
                if(this.goodsGroup[index]){
                    this.goods                  =   this.goodsGroup[index];
                }else {
                    if(this.category){
                        const that = this;
                        this.goodsLoading = true;
                        goodsApi.list(this.filters[index].query).then(response=>{
                            that.goodsLoading       =   false;
                            that.goods              =   response.data;
                            that.goodsGroup[index]  =   that.goods;
                        });
                    }
                }

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
                    adApi.list({position:'pc_index_category_cover_'+this.category.id}).then(response=>{
                        that.adLoading = false;
                        if(response.data.length > 0){
                            that.adCover = response.data[0];
                        }
                    })
                }
            },
            mouseOver(index){
                const that = this;
                this.filters.forEach(function (filter) {
                    const index                 =   that.filters.indexOf(filter);
                    filter.active               =   false;
                    that.$set(that.filters,index,filter);
                });
                this.filters[index].active = true;
                this.getGoods(index)
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
    .index-category-box .category-title{
        overflow: hidden;
        height: 40px;
        border-width: 0;
        border-bottom-width: 3px;
        border-style: solid;
        width: 1140px;

        border-color: $border-default;
        li{
            cursor: pointer;
            &.active{
                color: red;
            }
        }

    }
    .index-category-box .category-title .title{
        float: left;
        font-size: 24px;
        width: 200px;
        height: 40px;
        line-height: 40px;
        text-align: center;
        color: white;
        background-color: $navbar-default-bg;
    }
    .index-category-box .category-title ul{
        float: left;

    }
    .index-category-box .category-title ul li{
        float: left;
        width:240px;
        text-align: center;
        font-size:14px;
        font-weight:600;
        line-height: 40px;
    }
    .index-category-box .category-children{
        height: 410px;
        float: left;
        width:200px;
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
        height: 410px;
        .adp-goods{
            width:640px;
            border-right: 1px solid #e8e8e8;
            .adp{
                height: 260px;
                width: 640px;
                img{
                    width: 640px;
                    height:260px;
                }
            }
            .adp-b-goods{
                width:640px;
                height: 150px;
                li{
                    width: 159px;
                    float:left;
                    border-bottom: 1px solid #e8e8e8;
                    border-right: 1px solid #e8e8e8;
                    height: 139px;
                    padding-top: 10px;
                    &:last-child{
                        border-right: 0;
                    }
                    .cover{
                        img{
                            width: 100px;
                            display:block;
                            margin:auto;
                            transition: all 0.4s ease-out 0s;
                            -ms-transition: all 0.4s ease-out 0s;
                            -webkit-transition: all 0.4s ease-out 0s;
                        }
                    }
                    p{
                        text-align:center;
                    }
                    .name{
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                    }
                    .price{

                        color:#fb4d72;
                    }
                    &:hover{
                        .cover {
                            img{
                                transform:scale(1.03);
                                -ms-transform:scale(1.03);
                                -webkit-transform:scale(1.03);
                            }
                        }
                    }

                }
            }
        }
        .goods{
            width:298px;
            height:410px;
            float:left;
            li{
                width: 148px;
                float:left;
                height:204px;
                overflow:hidden;
                border-bottom: 1px solid #e8e8e8;
                border-right: 1px solid #e8e8e8;
                p{
                    text-align:center;
                    line-height: 30px;
                }
                .cover{
                    width: 148px;
                    height: 140px;
                    overflow:hidden;
                    img{
                        transition: all 0.4s ease-out 0s;
                        -ms-transition: all 0.4s ease-out 0s;
                        -webkit-transition: all 0.4s ease-out 0s;
                    }
                }
                .price{

                    color:#fb4d72;
                }
                &:hover{
                    img{
                        transform:scale(1.03);
                        -ms-transform:scale(1.03);
                        -webkit-transform:scale(1.03);
                    }
                }
            }
        }

    }
</style>