<template>
    <div class="relation-brand" v-if="list.length > 0">
        <p class="title">{{$t('brand.relation_brand')}}</p>
        <div class="list" v-loading="loading">
            <ul>
               <li class="item" v-for="item in list">
                   <a :href="getRoute(item.id)" target="_blank">{{item.name}}</a>
               </li>
            </ul>
        </div>
    </div>
</template>

<script>
    import goodsCategoryApi from '../api/goodsCategory'
    import brandApi from "../api/brand";
    import config from "../config";
    export default {
        name: "relation-brand",
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
                brandApi.list({category_id:this.category_id}).then(response => {
                    that.list               =   response.data;
                    that.loading            =   false;
                })
            },
            getRoute(id){
                return config.baseApi+'/brand/'+id;
            }
        }
    }
</script>

<style scoped lang="scss">
    .relation-brand{
        margin-top: 10px;
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