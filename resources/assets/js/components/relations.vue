<template>
    <div class="relations">
        <div class="list">
            <ul>
                <li class="item" v-for="item in list">
                    <div class="cover">
                        <img :src="item.cover">
                    </div>
                    <p class="price">
                        {{item.price}}
                    </p>
                    <p class="goods-name">
                        {{item.goods_name}}
                    </p>

                    <div class="tools">
                        <a class="go-info" :href="getGoodsRoute(item.relation_goods_id)" target="_blank">
                            {{$t('goods.go_info')}}
                        </a>
                        <a class="collect" href="javascript:void (0)" @click="collect">
                            <i class="iconfont icon-tuijian"></i>
                            {{$t('operate.collect')}}
                        </a>
                    </div>

                </li>
            </ul>
        </div>
    </div>
</template>

<script>


    import goodsApi from "../api/goods";
    import collectionApi from "../api/collection";
    import config from "../config";

    export default {
        name: "relations",
        data(){
            return {
                list:[]
            }
        },
        props:{
            goods_id:0
        },
        created(){
            this.getRelation();
        },
        methods:{
            getRelation(){
                const that                      =   this;
                goodsApi.relation(this.goods_id).then(response => {
                    if(response){
                        that.list               =   response.data;
                    }
                })
            },
            getGoodsRoute(id){
                return config.baseApi+'/goods/'+id;
            },
            collect(id){
                const that                      =   this;
                collectionApi.collect({goods_id:id}).then(response => {
                    if(response){
                        that.$message({
                            message:that.$t('collection.collect_success')
                        })
                    }
                })
            }
        }
    }
</script>

<style scoped lang="scss">
    .relations{
        padding: 20px 40px ;
        .list{
            overflow:hidden;
        }
        .item{
            width: 180px;
            float:left;
            margin-right: 20px;
            &:nth-child(4n){
                margin-right: 0;
            }
            .cover{
                width: 180px;
                padding-top: 10px;
                padding-bottom: 10px;
                overflow: hidden;
                img{
                    width: 180px;
                    display: block;
                    margin: auto;
                }
            }
            .price{
                color: #ff4f5e;
            }
            .goods-name{
                color: #999999;
                line-height: 2;
            }
            .tools{
                padding:15px 0;
                .go-info{
                    border: 1px solid #ccc;
                    border-radius: 3px;
                    padding: 5px 10px;
                    float: left;
                    color: #999999;
                }
                .collect{
                    float: right;
                    padding: 5px 10px;
                    color: #999999;
                }
            }

        }
    }

</style>