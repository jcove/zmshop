<template v-if="ad">
    <div class="banner container" v-if="ad">
        <a :href="ad.link" target="_blank">
            <img :src="ad.code">
        </a>
    </div>
</template>

<script>
    import adApi from "../../api/ad";
    export default {
        name: "banner",
        data(){
            return {
                ad:null
            }
        },
        props:{
            position:''
        },
        created(){
            this.getBanner();
            console.log(this.position)
        },
        methods:{
            getBanner(){
                if(this.position){
                    adApi.list( { position:this.position}).then( response => {
                        if(response && response.data.length > 0){
                            this.ad = response.data[0];
                        }
                    })
                }

            }
        }
    }
</script>

<style scoped lang="scss">
    .banner{
        img{
            width: 1140px;
            height: 90px;
        }
    }
</style>