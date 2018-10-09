<template>
    <div class="">
        <el-steps :active="active">
            <el-step :title="$t('order.wait_package')" icon="iconfont icon-file"></el-step>
            <el-step :title="$t('order.transport')" icon="iconfont icon-fahuo"></el-step>
            <el-step :title="$t('order.dispatch')" icon="iconfont icon-paisongzhong"></el-step>
            <el-step :title="$t('order.received')" icon="iconfont icon-qianshou"></el-step>
        </el-steps>
        <div class="data" v-loading="loading">
            <ul>
                <li v-for="item in data">
                    <span class="time">{{item.time}}</span>
                    <span class="context">{{item.context}}</span>
                </li>
            </ul>
        </div>
        <div class="express-info">
            <span>{{$t('order.express_sn')}}:{{postId}}</span>
            <span>{{$t('order.express_name')}}:{{expressName}}</span>
        </div>
    </div>


</template>

<script>
    import api from "../api";

    export default {
        name: "express",
        data(){
            return {
                active:1,
                data:[],
                loading:false
            }
        },
        props:{
            com:'',
            postId:'',
            expressName:''
        },
        created(){
            this.getExpress();
            console.log('created')
        },
        watch:{
            postId(value){
                this.getExpress();
                console.log('updated')
            }

        },
        methods:{
            getExpress(){
                api.setPath('express/query');
                const that = this;
                this.loading = true;
                api.list({com:this.com,post_id:this.postId}).then(response => {
                    if(response.status==="200"){
                        that.data                   =   response.data;
                        switch (response.state){
                            case '0':
                                that.active         =   2;
                                break;
                            case '5':
                                that.active         =   3;
                                break;
                            case '3':
                                that.active         =   4;
                                break;
                        }
                    }
                    that.loading = false

                })
            }
        }
    }
</script>

<style scoped lang="scss">
    .data{
        background-color: #f3f3f3;
        border-radius:5px;
        padding:15px  20px;
        ul{
            li{
                line-height: 2;
                padding:10px;
                .time{
                    display: inline-block;
                    width: 200px;
                }
            }
        }
    }
    .express-info{
        padding: 15px 20px;
        span{
            display: inline-block;
            width: 300px;
        }
    }

</style>