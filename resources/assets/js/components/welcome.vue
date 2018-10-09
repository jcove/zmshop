<template>
    <div class="bg"  :style="{ display:display}">
        <div class="welcome container" :style="{ display:display,backgroundImage:welcomeBg }" >
            <!--<div class="title">-->
                <!--<p class="cn">{{$t('welcome.title_cn')}}</p>-->
                <!--<p class="en">{{$t('welcome.title_en')}}</p>-->
            <!--</div>-->
            <div class="move">
                <div class="panda" :style="{left:left+'px',bottom:bottom+'px'}">
                    <img class="body" :src="panda" />
                </div>
                
            </div>
            <div class="lang">
                <p class="cn" @click="setLang('cn')">中文</p>
                <p class="en" @click="setLang('en')">English</p>
                <p class="jp" @click="setLang('jp')">日本語</p>
            </div>
        </div>
    </div>
</template>

<script>
    import config from "../config";

    export default {
        name: "welcome",
        data(){
            return {
                display:'block',
                welcomeBottomBg:'images/welcome_bottom.png',
                welcomeBg:'url("images/welcome_bg.png")',
                panda:'images/panda.gif',
                left:0,
                bottom:-80,
                footer:'',
                moves:[
                    'images/panda_3.png',
                    'images/panda_1.png',
                    'images/panda_2.png',
                ]
            }
        },
        created(){

            //this.walk();
            if(localStorage.getItem('lang')){
                this.display = 'none';
            }else {
                this.move();
            }

        },
        methods:{
            move(){
                const that = this;
                var ps = [{ x: 0, y: -80 }, { x: 100, y: -70 }, { x: 200, y: -60 }, { x: 300, y: -50 }, { x: 400, y: -40 }, { x: 500, y: 0 }, { x: 600, y: -20 }, { x: 700, y: -40 }, { x: 800, y: -60 }, { x: 1100, y: -80 }];
                const guijipoints= this.CreateBezierPoints(ps, 1000);
                // setInterval(function () {
                //     if(that.left > 940){
                //         that.left       =   0;
                //     }
                //     that.left +=20;
                //     that.bottom = 0.2*that.left*that.left/(800*that.left)-60;
                // },500)
                var index = 0;
                setInterval(function () {
                    var p = guijipoints[index];
                    that.left= p.x;
                    that.bottom =p.y;
                    index++;
                    if (index >= guijipoints.length) {
                        index = 0;
                    }
                }, 1000 / 100);
            },
            walk(){
                const length = this.moves.length;
                const that      =   this;
                var i=0;
                setInterval(function () {
                    if(i >= length){
                        i               =   0;
                    }
                    that.panda         =   that.moves[i];
                    i++
                },400);
            },
            setLang(lang){
                if(!config.debug){
                    location.href = 'http://'+lang + '.'+ config.domain + '?lang='+lang
                }else {
                    location.href = config.baseApi + '?lang=' + lang;
                }

            },
            CreateBezierPoints(anchorpoints, pointsAmount) {
                var points = [];
                for (var i = 0; i < pointsAmount; i++) {
                    var point = this.MultiPointBezier(anchorpoints, i / pointsAmount);
                    points.push(point);
                }
                return points;
            },
            MultiPointBezier(points, t) {
                var len = points.length;
                var x = 0, y = 0;
                var erxiangshi = function (start, end) {
                    var cs = 1, bcs = 1;
                    while (end > 0) {
                        cs *= start;
                        bcs *= end;
                        start--;
                        end--;
                    }
                    return (cs / bcs);
                };
                for (var i = 0; i < len; i++) {
                    var point = points[i];
                    x += point.x * Math.pow((1 - t), (len - 1 - i)) * Math.pow(t, i) * (erxiangshi(len - 1, i));
                    y += point.y * Math.pow((1 - t), (len - 1 - i)) * Math.pow(t, i) * (erxiangshi(len - 1, i));
                }
                return { x: x, y: y };
            }
        }
    }
</script>

<style scoped lang="scss">
    .bg{
        position: fixed;
        width: 100%;
        height: 100%;
        background-color: white;
        top: 0;
        z-index: 2001;
        .welcome{
            height: 625px;
            background-size: 1140px;
            position: relative;
            .title{
                background-color: #037af8;
                border-bottom:10px solid #0066d4;
                height: 200px;
                text-align: center;
                padding-top: 50px;
                p{
                    color:white;
                    font-size:40px;
                    line-height: 2;
                }
                .en{
                    font-size:30px;
                }
            }
            .move{
                height: 534px;
                position: relative;
                .panda{
                    position: absolute;
                    bottom: -20px;
                    height: 200px;
                    z-index: 3000;
                    .body{
                        width: 200px;
                        position: absolute;
                    }
                    .footer{
                        width: 100px;
                        position: absolute;
                        bottom: 0px;
                        background: none;
                    }
                }
            }
            .lang{
                position: absolute;
                bottom: 0;
                img{
                    width: 1140px;
                }
                p{
                    padding: 8px;
                    background-color: white;
                    position: absolute;
                    bottom: 15px;
                    font-size:18px;
                    cursor: pointer;
                    width: 70px;
                }
                .cn{
                    left: 50px;

                }
                .en{
                    left: 490px;
                }
                .jp{
                    left: 980px;
                }

            }
        }
    }
</style>