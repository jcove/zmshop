<template>
    <div class="relation-category">
        <p class="title">{{$t('goods_category.relation_category')}}</p>
        <div class="list" v-loading="loading">
            <ul>
               <li class="item" v-for="item in list">
                   <a :href="getRoute(item.id)">{{item.name}}</a>
               </li>
            </ul>
        </div>
    </div>
</template>

<script>
    import goodsCategoryApi from '../api/goodsCategory'
    import config from "../config";
    export default {
        name: "relation-category",
        data(){
            return {
                list:[],
                loading:false
            }
        },
        props:{
            category_id:0
        },
        created(){
            this.getRelations();
        },
        methods:{
            getRelations(){
                const that                  =   this;
                this.loading                =   true;
                goodsCategoryApi.relations(this.category_id).then(response => {
                    that.list               =   response.data;
                    that.loading            =   false;
                })
            },
            getRoute(id){
                return config.baseApi+'/goodsCategory/'+id;
            }
        }
    }
</script>

<style scoped lang="scss">
    .relation-category{
        width:180px;
        margin:auto;
        border:1px solid #e8e8e8;
        .title{
            font-size: 18px;
            color: #666;
            text-align: center;
            background-color: #e8e8e8;
            padding: 15px 0;
        }
        .list{
            padding-top:5px;
            min-height: 100px;
            ul{
                overflow: hidden;
                .item{
                    float: left;
                    width: 90px;
                    text-align: center;
                    padding: 10px 0;
                    color: #666;
                }
            }

        }
    }
</style>