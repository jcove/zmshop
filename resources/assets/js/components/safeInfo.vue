<template>
    <div class="user-safe">
        <div class="user">
            <div class="avatar">
                <img v-if="user.avatar" :src="user.avatar"/>
                <img v-else :src="defaultAvatar"/>
            </div>
            <div class="nick">
                {{$t('user.username')}}:{{user.mobile}},{{user.nick}}
            </div>
        </div>
        <div class="safe">
            <div class="item">
                <div class="icon">
                    <i class="iconfont icon-lock"></i>
                </div>
                <div class="name">
                    {{$t('operate.modify_password')}}
                </div>
                <div class="tips">
{{$t('user.password_tips')}}
                </div>
                <div class="operate">
                    <el-button type="primary" @click="showModifyPasswordForm">{{$t('operate.modify')}}</el-button>
                </div>
            </div>
        </div>
        <el-dialog
                :title="$t('operate.modify_password')"
                :visible.sync="dialogModifyPasswordVisible"
                width="400px">
            <el-form>
                <el-form-item label-width="0px">
                    <el-input type="password" v-model="passwordForm.old_password" :placeholder="$t('user.old_password')">
                        <i slot="prefix" class="iconfont icon-lock"></i>
                    </el-input>
                </el-form-item>
                <el-form-item label-width="0px">
                    <el-input type="password" v-model="passwordForm.password" :placeholder="$t('user.new_password')">
                        <i slot="prefix" class="iconfont icon-lock"></i>
                    </el-input>
                </el-form-item>
                <el-form-item label-width="0px">
                    <el-input type="password" v-model="passwordForm.password_confirmation" :placeholder="$t('user.password_confirmation')">
                        <i slot="prefix" class="iconfont icon-lock"></i>
                    </el-input>
                </el-form-item>
                <el-form-item label-width="140px">
                    <el-button type="primary" @click="savePassword">确 定</el-button>
                </el-form-item>
            </el-form>

        </el-dialog>
    </div>

</template>

<script>


    import userApi from "../api/user";

    export default {
        name: "safe-info",
        data(){
          return {
              defaultAvatar:'/images/avatar.jpg',
              dialogModifyPasswordVisible:false,
              passwordForm:{
                  old_password:'',
                  password:'',
                  password_confirmation:''
              }
          }
        },
        props:{
            user:Object
        },
        methods:{
            savePassword(){
                const that                  =   this;
                userApi.modifyPassword(this.passwordForm).then(response => {
                    if(response){
                        that.$message({
                            message:that.$t('common.success'),
                            type:'success'
                        });
                        that.dialogModifyPasswordVisible =false;
                    }
                });
            },
            showModifyPasswordForm(){
                this.dialogModifyPasswordVisible=true;
            },

        }
    }
</script>

<style scoped lang="scss">
    .user-safe{
        padding: 20px;
        overflow:hidden;
        .user{
            border:1px solid #35a0fc;
            overflow: hidden;
            .avatar{
                float:left;
                width:180px;
                padding:20px;
                img{
                    width:100px;
                    height:100px;
                    border: 1px solid #ccc;
                    display: block;
                    margin: auto;
                }
            }
            .nick{
                line-height: 100px;
                float: left;
                padding: 20px;
            }
        }
        .safe{
            border: 1px solid #35a0fc;
            padding: 20px;
            border-top: 0;
            .item{
                overflow: hidden;
                line-height: 30px;
                .icon,.name,.tips,.operate{
                    float: left;
                }
                .icon{
                    width: 60px;
                    .iconfont{
                        font-size: 34px;
                        color: #35a0fc;
                    }
                }
                .name{
                    width: 120px;
                    font-size: 18px;
                    color: #35a0fc;
                }
                .tips{
                    width: 518px;
                }
                .operate{
                    width: 120px;
                }
            }

        }
    }
</style>