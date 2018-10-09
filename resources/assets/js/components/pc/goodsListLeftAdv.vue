<template>
    <div class="goods-list-left-adp">
        <div v-for="item in list" class="item">
            <a :href="item.link">
                <img :src="item.code">
            </a>
        </div>
    </div>
</template>

<script>
    import adApi from "../../api/ad";

    export default {
        name: "goods-list-left-adv",
        data(){
          return {
              list: []
          }
        },
        created(){
            adApi.list({ position: 'pc_goodsList_left'}).then(response =>{
                if(response){
                    this.list = response.data
                }
            })
        }
    }
</script>

<style scoped lang="scss">
    .goods-list-left-adp{
        .item{
            img{
                width: 239px;
                height: 239px;
            }
        }
    }
</style>