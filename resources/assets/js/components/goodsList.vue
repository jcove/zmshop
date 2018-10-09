<template>
    <div class="common-goods-list">

        <div class="goods-list">
            <div class="sort-bar">
                <a v-for="item in sorts" href="javascript:void (0)" :class="{active:item.active}" @click="sort(item.key)">{{item.lang}}<i class="iconfont icon-down"></i>
                </a>
            </div>
            <div class="list" v-loading="listVisible">

                <template v-if="list.length > 0">
                    <div class="item" v-for="item in list">
                        <a :href="goodsRoute(item.id)">
                            <div class="cover">
                                <img :src="item.cover">
                            </div>
                            <div class="price">
                                <span>{{$t('goods.$')}}:{{item.price}}</span>
                            </div>
                            <div class="name">
                                <p>{{item.name}}</p>
                            </div>
                        </a>

                    </div>
                </template>


            </div>
            <div class="pages">
                <el-pagination
                        layout="prev, pager, next"
                        :total="page.total"
                        :page-size="page.pageSize"
                        background
                        :current-page="query.page"
                        @current-change="pageChange" @prev-click="prevClick" @next-click="nextClick">
                </el-pagination>
            </div>
        </div>
    </div>
</template>

<script>
    import goodsApi from "../api/goods";
    import config from "../config";

    export default {
        name: "goods-list",
        data(){
            const that                  =   this;
            return {
                list:[],
                sorts:[
                    {
                       key:'synthesized',
                       active:true ,
                        lang:that.$t('goods.synthesized')
                    },
                    {
                        key:'sales',
                        active:false,
                        lang:that.$t('goods.sales')
                    },
                    {
                        key:'prices',
                        active:false,
                        lang:that.$t('goods.price')
                    }
                ],
                query:{
                    page:1,
                    sort:'synthesized'
                },
                page:{
                    total:0,
                    pageSize:12
                },
                listVisible:true
            }
        },
        props:{
            filter:Object
        },
        created(){
            this.initQuery();
            this.getList();

        },
        methods:{
            initQuery(){
                const keys                      =   Object.keys(this.filter);
                const that                      =   this;
                keys.forEach(function (key) {
                    that.$set(that.query,key,that.filter[key]);
                });
            },
            sort(sort){
                const that              =   this;
              this.sorts.forEach(function (item) {
                  const index                   =   that.sorts.indexOf(item);
                  if(item.key === sort){
                      item.active               =   true;
                      that.query.sort           =   sort;
                      that.query.page           =   1;
                  }else {
                      item.active               =   false;
                  }
                  that.$set(that.sorts,index,item);
              });
              this.getList();
            },
            getList(){
                const that                  =   this;
                this.listVisible            =   true;
                goodsApi.list(this.query).then( response => {
                   if(response){
                       that.list                =   response.data;
                       that.page.total          =   response.total;
                       that.page.pageSize       =   parseInt(response.per_page);
                   }
                    this.listVisible            =   false;
                });
            },
            goodsRoute(id){
                return config.baseApi+'/goods/'+id;
            },
            pageChange(page) {
                this.query.page = page;
                this.getList();
            },
            prevClick() {
                this.query.page = this.query.page--;
                if(this.query.page < 1){
                    this.query.page = 1
                }
                this.getList()
            },
            nextClick() {
                this.query.page = this.query.page++;
                this.getList()
            },
        }
    }
</script>

<style scoped lang="scss">
    .goods-list{
        width: 861px;
        margin-top: 20px;
        .sort-bar{
            background-color: #f9f9f9;
            border:1px solid #e8e8e8;
            a{
                border-right: 1px solid #e8e8e8;
                display: inline-block;
                line-height: 30px;
                padding: 10px;
                text-align: center;
                width: 100px;
                &:last-child{
                    border-right: 0;
                }
            }
            .active{
                color: red;
            }
        }
        .list{
            overflow: hidden;
            .item{
                float: left;
                width: 200px;
                border: 1px solid #f8f8f8;
                margin-right: 15px;
                margin-top: 15px;
                &:nth-child(4n){
                    margin-right: 0;
                }
                .cover{
                    height: 200px;
                    img{
                        max-width:100%;
                    }
                }
                .price{
                    padding: 5px;
                    span{
                        color: #c90418;
                    }
                }
                .name{
                    padding: 5px;
                }
            }
        }

    }
</style>