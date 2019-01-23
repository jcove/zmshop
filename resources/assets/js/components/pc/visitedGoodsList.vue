<template>
    <div class="visited-goods-list">
        <ul>
            <li v-for="goods in list">
                <a :href="getRoute(goods.id)" target="_blank">
                    <div class="cover">
                        <img :src="goods.cover">
                    </div>
                </a>
                <a :href="getRoute(goods.id)" target="_blank">
                <p class="name">
                    {{goods.name}}
                </p>
                </a>
                <p class="price">
                    {{$t('goods.$')}}{{goods.price}}
                </p>
            </li>
        </ul>
    </div>
</template>

<script>
    import config from "../../config";

    export default {
        name: "visited-goods-list",
        data(){
            return {
                list: []
            }
        },
        created(){
            this.list =JSON.parse(localStorage.getItem('visited_goods_list')) ||  new Array();

        },
        methods:{
            getRoute(id){
                return config.baseApi + '/goods/'+id
            }
        }
    }
</script>

<style scoped lang="scss">
    .visited-goods-list{
        margin-top: 20px;
        width:237px;
        border: 1px solid #E8E8E8;
        ul{
            overflow:hidden;
        }
        li{
            width:237px;
            float:left;
            height: 300px;
            .cover{
                img{
                    width: 200px;
                    height: 200px;
                    display: block;
                    margin: auto;
                }
            }
            .name{
                text-align: center;
                height: 60px;
                padding: 5px;
                overflow: hidden;
            }
            .price{
                text-align:center;
                color:red;
            }

        }
    }
</style>