<template>
    <el-dialog
            title=""
            :visible.sync="centerDialogVisible"
            width="30%"
            :close-on-click-modal="false"
            :close-on-press-escape="false"
            :show-close="false"
            center>
        <div class="message">
            <span v-loading="true" style="height:20px;width:40px"></span>
            <span>{{$t('order.waiting_go_pay')}}</span>

        </div>
    </el-dialog>
</template>

<script>
    import orderApi from "../api/order";

    export default {
        name: "go-pay-message",
        data() {
            return {
                centerDialogVisible: true
            };
        },
        props:{
            id:0
        },
        mounted(){
          this.goPay();
        },
        methods:{
            goPay(){
                if(this.id && this.id > 0){
                    orderApi.pay(this.id).then(response => {
                        if(response.pay_url){
                            location.href           =   response.pay_url;
                        }
                    })
                }

            }
        }
    }
</script>

<style scoped lang="scss">
    .message{
        text-align: center;
        span{
            display: inline-block;
        }
    }
</style>