<template>
    <div class="suggestion-form">
        <el-input
                type="textarea"
                :rows="10"
                :placeholder="$t('user.suggestion_text')"
                v-model="form.content">
        </el-input>
        <div class="block">
            <el-button type="primary" @click="submit">
                {{$t('operate.submit')}}
            </el-button>
        </div>
    </div>

</template>

<script>
    import suggestionApi from "../api/suggestion";
    export default {
        name: "suggestion-form",
        data(){
            return {
                form:{
                    content:''
                }
            }
        },
        methods:{
            submit(){
                const  that                 =   this;
                suggestionApi.save(this.form).then(response=> {
                    if (response) {
                        that.$message({
                            message:that.$t('common.submit_success'),
                            type:'success'
                        })
                        that.form.content= '';
                    }
                });
            }
        }
    }
</script>

<style scoped lang="scss">
    .suggestion-form{
        padding: 10px;
    }
    .block{
        padding: 30px;
        text-align: center;
    }
</style>