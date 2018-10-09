<template>
    <div class="goods-comments">
        <div class="comments">
            <div class="item" v-for="item in list">
                <div class="user">
                    <div class="avatar">
                        <img v-if="item.avatar" :src="item.avatar">
                        <img :src="defaultAvatar"/>
                    </div>
                    <div class="nick">
                        {{item.nick}}
                    </div>
                </div>
                <div class="content">
                    <div class="rank">
                        <i class="iconfont icon-star" v-for="i in item.goods_rank"></i>
                        <i class="iconfont icon-tuijian" v-for="j in 5-item.goods_rank"></i>
                    </div>
                    <p>
                        {{item.content}}
                    </p>

                </div>
                <div class="date">
                    <p>
                        {{item.created_at}}
                    </p>
                </div>
            </div>
        </div>

        <el-pagination
                layout="prev, pager, next"
                :total="page.total"
                :page-size="page.pageSize"
                @current-change="pageChange" @prev-click="prevClick" @next-click="nextClick">
        </el-pagination>

    </div>
</template>

<script>
    import commentApi from '../api/comment'
    export default {
        name: "goods-comments",
        props:{
            comments:{
                type:Array,
                default:[]
            },
            total:0,
            perPage:0
        },
        data(){
            const  that = this;
            return {
                list:[],
                page:{
                    total:0,
                    pageSize:0
                },
                query:{
                    page:1,
                },
                defaultAvatar:'/images/avatar.jpg'
            }
        },
        created(){
          this.list             =   this.comments;
          this.page.total       =   this.total;
          this.page.pageSize    =   this.perPage
        },
        methods:{

            getComments(){
                const that                      =   this;
                commentApi.user(this.query).then(response => {
                    that.page.total             =   response.total;
                    that.list                   =   response.data;
                })
            },
            pageChange(page) {
                this.query.page = page;
                this.getComments();
            },
            prevClick() {
                this.query.page = this.query.page--;
                if(this.query.page < 1){
                    this.query.page = 1
                }
                this.getComments()
            },
            nextClick() {
                this.query.page = this.query.page++;
                this.getComments()
            },
        }
    }
</script>

<style scoped lang="scss">
    .goods-comments{
        .comments {
            .item{
                overflow:hidden;
                padding:10px;
                border-bottom:1px solid #e8e8e8;
                .user{
                    width:140px;
                    float:left;
                    .avatar{
                        border:1px solid #e8e8e8;
                        border-radius:5px;
                        width:70px;
                        height:70px;
                        margin:auto;
                        img{
                            width:60px;
                            display:block;
                            margin:auto;
                        }
                    }
                    .nick{
                        text-align:center;
                    }
                }
                .content{
                    width:500px;
                    float:left;
                    .rank{
                        height:40px;
                    }
                    p{

                    }
                }
                .date{
                    width:140px;
                    float:left;
                    padding-right:15px;
                    padding-top:10px;

                }
                .icon-star{
                    color:#ffa800;
                }
            }

        }
    }
</style>