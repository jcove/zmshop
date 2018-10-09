<template>
    <div class="order-goods">
        <h3 class="title">
            {{$t('order.goods_comment')}}
        </h3>
        <div class="comment-form" v-for="(goods,index) in list">
            <div class="goods">
                <div class="cover">
                    <img :src="goods.cover"/>
                </div>
                <div class="name">
                    {{goods.name}}
                </div>
            </div>
            <div class="form">
                <el-input style="width: 500px;"
                          type="textarea"
                          :rows="8"
                          :placeholder="$t('order.comment_text')"
                          v-model="form.comments[index].content">
                </el-input>
                <div class="rank">
                    <div class="name">
                        {{$t('order.express_rank')}}
                    </div>
                    <el-rate style="float:left;padding:10px"
                             v-model="form.comments[index].express_rank" allow-half
                             :colors="['#99A9BF', '#F7BA2A', '#FF9900']">
                    </el-rate>
                </div>
                <div class="rank">
                    <div class="name">
                        {{$t('order.goods_rank')}}
                    </div>
                    <el-rate style="float:left;padding:10px"
                             v-model="form.comments[index].goods_rank" allow-half
                             :colors="['#99A9BF', '#F7BA2A', '#FF9900']">
                    </el-rate>
                </div>
                <div class="rank">
                    <div class="name">
                        {{$t('order.service_rank')}}
                    </div>
                    <el-rate style="float:left;padding:10px"
                             v-model="form.comments[index].service_rank" allow-half
                             :colors="['#99A9BF', '#F7BA2A', '#FF9900']">
                    </el-rate>
                </div>
            </div>
        </div>
        <div class="submit-box">
            <el-button @click="submit()" type="primary">{{$t('operate.submit_comment')}}</el-button>
        </div>
    </div>
</template>

<script>
    import commentApi from "../api/comment";
    import orderApi from "../api/order";
    import config from "../config";

    export default {
        name: "comment-form",
        data() {
            return {
                list: [],
                form: {
                    "order_id": 0,
                    "comments": []
                }
            }
        },
        props: {
            id: 0
        },
        created() {
            this.getOrder();
            this.form.order_id = this.id;

        },
        methods: {
            submit() {
                const that              =   this;
                commentApi.comment(this.form).then(response => {
                    if(response){
                        that.$message({
                           message:that.$t('order.comment_success'),
                           type:'success'
                        });
                        location.href = config.baseApi+'/order';
                    }
                })
            },
            getOrder(){
                const that = this;
                orderApi.get(this.id).then(response => {
                    that.list = response.data.order_goods;
                    let array = [];
                    response.data.order_goods.forEach(function (item) {
                        let comment = {
                            goods_id: item.goods_id,
                            content: "",
                            images: "",
                            express_rank: 0,
                            goods_rank: 0,
                            service_rank: 0
                        }
                        array.push(comment)

                    });
                    that.form.comments = array;
                })
            }
        }
    }
</script>

<style scoped lang="scss">
    .order-goods {
        .title {
            font-weight: normal;
            padding: 10px;
        }
        .comment-form {
            border: 1px solid #e8e8e8;
            overflow: hidden;
            padding-top: 30px;
            padding-bottom: 30px;
            .goods {
                width: 400px;
                float: left;
                .cover {
                    margin: auto;
                    img {
                        width: 280px;
                        display: block;
                        margin: auto;
                    }
                }
            }
            .form {
                float: left;
                .rank {
                    overflow: hidden;
                    .name {
                        float: left;
                        width: 100px;
                        padding: 10px;
                    }
                }
            }
        }
        .submit-box {
            padding: 30px;
            text-align: center;
        }
    }
</style>