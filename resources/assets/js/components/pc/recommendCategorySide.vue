<template>
    <div class="recommend-category-side">
        <div class="cover">
            <p class="title">
              {{$t('common.recommend')}}
            </p>
            <template v-if="ads.length > 0">
                <div class="swiper-container category-slider">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide" v-for="str in ads" :style="{ backgroundColor: str.background_color }">
                            <a :href="str.link" target="_blank"><img :src="str.code" ></a>
                        </div>
                    </div>
                </div>
            </template>

        </div>
        <div class="list" v-loading="cateLoading">
            <p class="title">
              {{$t('common.goods_category')}}
            </p>
            <ul>
               <li v-for="item in list">
                    <a :href="getCategoryRoute(item.id)" target="_blank">
                        <p class="icon">
                            <img :src="item.icon">
                        </p>
                        <p class="name">
                            {{item.name}}
                        </p>

                    </a>
               </li>
            </ul>
        </div>
    </div>
</template>

<script>
    import adApi from "../../api/ad";
    import goodsCategoryApi from "../../api/goodsCategory";
    import {getCategoryRoute} from "../../methods";
    import Swiper from 'swiper';
    export default {
        name: "recommend-category-side",
        data(){
            return {
                ads:[],
                list:[],
                cateLoading:false
            }
        },
        created(){
            this.getAd();
            this.getRecommendCategory();

        },
        methods:{
            getAd(){
                adApi.list({position:'pc_index_slide_right'}).then(response=>{
                    if(response){
                        if(response.data.length > 0){
                            this.ads = response.data;
                            this.initSwiper();
                        }
                    }
                })
            },
            getRecommendCategory() {
                const that = this;
                that.cateLoading = true;
                goodsCategoryApi.list({
                    is_show: 1,
                    is_recommend: 1,
                    all:1
                }).then(function (response) {
                    that.list = response.data;
                    that.cateLoading = false

                })
            },
            getCategoryRoute,
            initSwiper(){
                setTimeout(function () {
                    var swiper = new Swiper('.category-slider',{
                        loop: true,
                        paginationClickable: true,
                        autoplay:2000,
                        speed:1000
                    });
                },1000)

            }
        }
    }
</script>

<style scoped lang="scss">
    .recommend-category-side{
        position: absolute;
        background-color: rgba(255,255,255,0.8);
        left:950px;
        z-index: 2;
        width: 190px;
        overflow: hidden;
        height: 450px;
        .title{
            text-align: center;
            padding: 5px;
        }
        ul{
            overflow: hidden;
            li{
                width: 63px;
                float: left;
                padding: 15px 0;
            }
        }

        .icon,.name{
            width: 65px;
            text-align: center;
            font-size: 12px;
        }
        .icon{
            img{
                width: 20px;
                height: 20px;
                vertical-align: middle;
            }
        }

    }
    .cover{
        height: 150px;
        img{
            width: 190px;
            height: 120px;
        }
    }

</style>