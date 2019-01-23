<template>
    <div class="nav-category-tree " :style="{display:treeShow}" @mouseenter="handleTreeEnter()" @mouseleave="handleTreeLeave()">
        <div class="tree">
            <ul>
                <li v-for="item in tree" class="item" @mouseenter="enter(item)" @mouseleave="leave()">
                    <a :href="getCategoryRoute(item.id)">
                        <img :src="item.icon"/> {{item.name}}
                    </a>
                </li>
            </ul>
        </div>
        <div class="subs" :style="{ display: display}" @mouseenter="enter(null)" @mouseleave="leave()">
            <ul>
                <li v-for="item in subs">
                    <div class="level-2">
                        <a :href="getCategoryRoute(item.id)" target="_blank">{{item.name}}</a>
                    </div>
                    <div class="level-3">
                        <div class="sub" v-for="sub in item.child">
                            | <a :href="getCategoryRoute(sub.id)" >{{sub.name}}</a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    import config from "../../config";
    import { getCategoryRoute } from "../../methods";

    export default {
        name: "nav-category-tree",
        props:{
            data:{
                type:Array,
                default:[],
            },
            show:false
        },
        data(){
            return {
                tree:[],
                subs:[],
                display: 'none',
                treeShow:'none',
                active: false
            }
        },
        created(){
            this.tree       =   this.data;
            this.treeShow   =   this.show ? 'block' : 'none';
        },
        watch:{
          show:function (value) {
              if(!this.active){
                  this.treeShow = value ? 'block' : 'none'
              }

          }
        },
        methods:{
            getCategoryRoute,
            enter(item){
                this.display =  'block';
                this.active =  true;
                if(item != null){

                    if(item.child.length > 0){
                        this.subs  = item.child
                    }else {
                        this.subs  = []
                    }
                }

            },
            leave(){
                this.display =  'none';
            },
            handleTreeEnter(){
                this.treeShow = 'block';
                this.active = true;
            },
            handleTreeLeave(){
                this.treeShow = this.show ? 'block': 'none';
                console.log("lev")
                this.active = false;
            }
        }
    }
</script>

<style scoped lang="scss">
    @import "../../../sass/variables";
    .nav-category-tree{
        position: absolute;
        z-index: 999;
        top: 52px;
        display: none;

    }
    .active{
        display: block;
    }
    .tree{
        border-top: 0;
        width: 190px;
        overflow: hidden;
        float:left;
        height: 450px;
        background-color: rgba(255,255,255,0.95);
        .item{
            padding: 20px 30px;
            width: 156px;
            overflow: hidden;
            font-size: 16px;
            img{
                vertical-align: middle;
                width: 20px;
                height: 20px;
            }
            &:hover{
                background-color: $border-default;
                animation: fade-in;/*动画名称*/
                animation-duration: .5s;/*动画持续时间*/
                -webkit-animation:fade-in .5s;/*针对webkit内核*/
                padding-left: 40px;
                a{
                    color: white;
                }

            }
        }
    }
    .subs{
        float: left;
        width: 948px;
        border:2px solid $border-default;
        border-left: 0;
        border-top: 0;
        background-color: rgba(255,255,255,0.9);
        height: 448px;
        overflow:hidden;
        li{

            padding: 20px 10px 10px;
            text-align: center;
            width: 240px;
            float: left;
            .level-2{
                text-align: left;
                font-weight:bold;
                padding: 10px 0;
                a{
                    color: #666666;
                }
            }
            .level-3{
                .sub{
                    float: left;
                    padding-right: 10px;
                    font-size: 12px;
                    a{
                        color: #999999;
                    }
                }
            }
        }
    }
    @keyframes fade-in {
        0% {padding-left: 30px;background-color: rgba(255,255,255,0.8)}
        100% {padding-left: 40px; background-color: $navbar-default-bg;}
    }
    @-webkit-keyframes fade-in {/*针对webkit内核*/
        0% {padding-left: 30px;background-color: rgba(255,255,255,0.8)}
        100% {padding-left: 40px; background-color: $navbar-default-bg}
    }
</style>