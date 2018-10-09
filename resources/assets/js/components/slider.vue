<template>
    <div class="swiper-container slider">
        <div class="swiper-wrapper">
            <div class="swiper-slide" v-for="str in list" :style="{ backgroundColor: str.background_color }">
                <a :href="str.link"><img :src="str.code" ></a>
            </div>
        </div>
        <!-- 如果需要分页器 -->
        <div class="swiper-pagination"></div>
    </div>
</template>

<script>
    import Swiper from 'swiper';
    import 'swiper/dist/css/swiper.min.css';
    import addressApi from "../api/address";
    import adApi from "../api/ad";
    export default {
        name: "slider",
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
                var swiper = new Swiper('.slider',{

                    loop: true,

                    // 如果需要分页器
                    pagination: '.swiper-pagination',
                    paginationClickable: true,
                    autoplay:2000,
                    speed:1000
                });
            }
        }
    }
</script>

<style scoped lang="scss">
    .swiper-container {
        width: 100%;
        .swiper-wrapper {
            width: 100%;
            height: 450px;
        }
        .swiper-slide {
            background-position: center;
            background-size: cover;
            width: 100%;
            height: 450px;
            text-align: center;
            img {
                width: 760px;
                height: 450px;
            }
        }
        .swiper-pagination-bullet {
            display: inline-block;
            background: #7c5e53;
        }
    }
</style>