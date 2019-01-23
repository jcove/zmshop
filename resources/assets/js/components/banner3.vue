<template>
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <div class="swiper-slide" v-for="str in list" :style="{ backgroundImage: 'url(' + str.code + ')' }">
                <a :href="str.link" target="_blank"><img :src="str.code" ></a>
            </div>
        </div>
        <!-- 如果需要导航按钮 -->
        <div class="swiper-button-prev"><</div>
        <div class="swiper-button-next">></div>
    </div>
</template>

<script>
    import Swiper from 'swiper';
    import 'swiper/dist/css/swiper.min.css';
    import addressApi from "../api/address";
    import adApi from "../api/ad";
    export default {
        name: "banner3",
        data(){
            return {
                list:[]
            }
        },
        props: {
            data:{
                type:Array,
                default(){
                    return []
                }
            }
        },
        created(){
            this.list = this.data;

        },
        mounted(){
            this.initSwiper();
        },
        methods:{
            initSwiper(){
                var swiper = new Swiper('.swiper-container',{
                    autoplay:3000,
                    speed:1000,
                    autoplayDisableOnInteraction : false,
                    loop:true,
                    centeredSlides : true,
                    slidesPerView:2,

                    // 如果需要前进后退按钮
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    onInit:function(swiper){
                        swiper.slides[2].className="swiper-slide swiper-slide-active";//第一次打开不要动画
                    },
                    breakpoints: {
                        668: {
                            slidesPerView: 1,
                        }
                    }
                });
            }
        }
    }
</script>

<style scoped lang="scss">
    @media screen and (max-width: 668px) {
        .pc-banner {
            background-size: auto 100%;
        }
    }

    .swiper-container {
        width: 100%;
        margin: 35px 0;
    }

    @media screen and (max-width: 668px) {
        .swiper-container {
            margin: 20px 0 15px;
        }
    }

    .swiper-slide {
        -webkit-transition: transform 1.0s;
        -moz-transition: transform 1.0s;
        -ms-transition: transform 1.0s;
        -o-transition: transform 1.0s;
        -webkit-transform: scale(0.7);
        transform: scale(0.7);
    }

    @media screen and (max-width: 668px) {
        .swiper-slide {
            -webkit-transform: scale(0.97);
            transform: scale(0.97);
        }
    }

    .swiper-slide-active,.swiper-slide-duplicate-active {
        -webkit-transform: scale(1);
        transform: scale(1);
    }

    @media screen and (max-width: 668px) {
        .swiper-slide-active,.swiper-slide-duplicate-active {
            -webkit-transform: scale(0.97);
            transform: scale(0.97);
        }
    }

    .none-effect {
        -webkit-transition: none;
        -moz-transition: none;
        -ms-transition: none;
        -o-transition: none;
    }

    .swiper-slide a {
        background: #fff;
        display: block;
    }

    @media screen and (min-width: 668px) {
        .swiper-slide a:after {
            position: absolute;
            top: 0;
            left: 0;
            display: block;
            box-sizing: border-box;
            content: "";
            width: 100%;
            height: 100%;
           // background: url(../../images/top_slick_cover_bg01.png) 0 0 repeat;
        }
    }

    .swiper-slide-active a:after {
        background: none;
    }


    .swiper-slide img {
        width: 100%;
        display: block;
        height: 400px;
    }

    .swiper-pagination {
        position: relative;
        margin-bottom: 30px;
    }

    .swiper-pagination-bullet {
        background: #00a0e9;
        margin-left: 4px;
        margin-right: 4px;
        width: 17px;
        height: 17px;
        opacity: 1;
        margin-bottom: 4px;
    }

    .swiper-pagination-bullet-active {
        width: 13px;
        height: 13px;
        background: #FFF;
        margin-bottom: 0;
    }

    @media screen and (max-width: 668px) {

        .swiper-pagination {
            position: relative;
            margin-bottom: 20px;
        }

        .swiper-pagination-bullet {
            background: #00a0e9;
            margin-left: 2px;
            margin-right: 2px;
            width: 8px;
            height: 8px;
            margin-bottom: 2px;
        }

        .swiper-pagination-bullet-active {
            width: 6px;
            height: 6px;
            background: #FFF;
            margin-bottom: 0;
        }
    }

    .button {
        width: 1000px;
        margin: 0 auto;
        bottom: 43px;
        position: relative;
    }

    @media screen and (max-width: 668px) {
        .button {
            width: 70%;
            bottom: 22px;
        }
    }

    .button div:hover {
        background-color: #2f4798;
    }

    .swiper-button-prev {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background:  none;
        border: 2px solid #999;
        text-align: center;
        font-size: 26px;
        color: #999;
    }

    .swiper-button-next {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background:  none;
        border: 2px solid #999;
        text-align: center;
        font-size: 26px;
        color: #999;
    }

    @media screen and (max-width: 668px) {
        .button div {
            width: 28px;
            height: 28px;
        }
    }
</style>