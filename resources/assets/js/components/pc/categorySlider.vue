<template>
    <el-carousel :interval="5000" arrow="never" height="447px">
        <el-carousel-item v-for="ad in list" :key="ad.id">
            <a :href="ad.link" style="display: inline-block">
            <img :src="ad.code" style="width: 340px;height: 447px">
        </a>
        </el-carousel-item>
    </el-carousel>
</template>

<script>
    import Swiper from 'swiper';
    import 'swiper/dist/css/swiper.min.css';
    import adApi from "../../api/ad";
    export default {
        name: "category-slider",
        data(){
            return {
                list:[]
            }
        },
        props: {
            id:0
        },
        mounted(){
            this.getAd();
        },
        methods:{
            initSwiper(){
                if(this.list.length > 0){
                        var swiper = new Swiper('.slider-'+this.id,{
                            loop: true,
                            // 如果需要分页器
                            pagination: '.swiper-pagination',
                            paginationClickable: true,
                            autoplay:2000,
                            speed:1000
                        });
                }

            },
            getAd(){
                if(this.id){
                    const that = this;
                    this.adLoading = true;
                    adApi.list({position:'pc_index_category_cover_'+this.id}).then(response=>{
                        if(response.data.length > 0){
                            that.list = response.data;
                           // that.initSwiper()
                        }
                    });

                }
            },
        }
    }
</script>

<style scoped lang="scss">
    .swiper-container {
        width: 100%;
        .swiper-wrapper {
            width: 100%;
            height: 447px;
        }
        .swiper-slide {
            background-position: center;
            background-size: cover;
            width: 100%;
            height: 447px;
            text-align: center;
            img {
                width: 340px;
                height: 447px;
            }
        }
        .swiper-pagination-bullet {
            display: inline-block;
            background: #7c5e53;
        }
    }
</style>