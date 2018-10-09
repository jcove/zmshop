<div class="Cooldog_container">
    <div class="Cooldog_content">
        <ul>
            <li class="p1">
                <a href="#">
                    <img src="images/1.png" alt="">
                </a>
            </li>
            <li class="p2">
                <a href="#">
                    <img src="images/2.png" alt="">
                </a>
            </li>
            <li class="p3">
                <a href="#">
                    <img src="images/3.png" alt="">
                </a>
            </li>
            <li class="p4">
                <a href="#">
                    <img src="images/4.png" alt="">
                </a>
            </li>
            <li class="p5">
                <a href="#">
                    <img src="images/5.png" alt="">
                </a>
            </li>
            <li class="p5">
                <a href="#">
                    <img src="images/6.png" alt="">
                </a>
            </li>
            <li class="p5">
                <a href="#">
                    <img src="images/7.png" alt="">
                </a>
            </li>
        </ul>
    </div>
    <a href="javascript:;" class="btn_left">
        <i class="iconfont icon-zuoyoujiantou"></i>
    </a>
    <a href="javascript:;" class="btn_right">
        <i class="iconfont icon-zuoyoujiantou-copy-copy"></i>
    </a>
    <a href="javascript:;" class="btn_close">
        <i class="iconfont icon-icon-test"></i>
    </a>
    <div class="buttons clearfix">
        <a href="javascript:;" class="color"></a>
        <a href="javascript:;"></a>
        <a href="javascript:;"></a>
        <a href="javascript:;"></a>
        <a href="javascript:;"></a>
        <a href="javascript:;"></a>
        <a href="javascript:;"></a>
    </div>
</div>
<script>
    $(function () {
        //鼠标移入显示左右箭头和关闭按钮
        var timer = '';
        $('.Cooldog_container').mouseover(function () {
            $('.btn_left').show('1000');
            $('.btn_right').show('1000');
            $('.btn_close').show('1000');
            clearInterval(timer);
        }).mouseleave(function () {
            $('.btn_left').hide('1000');
            $('.btn_right').hide('1000');
            $('.btn_close').hide('1000');
            timer = setInterval(btn_right, 4000);
        });

        //点击关闭隐藏轮播图
        $('.btn_close').on('click', function () {
            $('.Cooldog_container').hide('1000');
        });

        var arr = ['p1', 'p2', 'p3', 'p4', 'p5', 'p6', 'p7'];
        var index = 0;

        //上一张
        $('.btn_left').on('click', function () {
            btn_left();
        });

        //下一张
        $('.btn_right').on('click', function () {
            btn_right();
        });

        //图片自动轮播
        timer = setInterval(btn_right, 4000);

        //点击上一张的封装函数
        function btn_left() {
            arr.unshift(arr[6]);
            arr.pop();
            $('.Cooldog_content li').each(function (i, e) {
                $(e).removeClass().addClass(arr[i]);
            })
            index--;
            if (index < 0) {
                index = 6;
            }
            show();
        }

        //点击下一张的封装函数
        function btn_right() {
            arr.push(arr[0]);
            arr.shift();
            $('.Cooldog_content li').each(function (i, e) {
                $(e).removeClass().addClass(arr[i]);
            })
            index++;
            if (index > 6) {
                index = 0;
            }
            show();
        }

        //点击底部的按钮切换图片
        $('.buttons a').each(function () {
            $(this).on('click', function () {
                var myindex = $(this).index();
                var mindex = myindex - index;
                if (mindex == 0) {
                    return;
                }
                else if (mindex > 0) {
                    var newarr = arr.splice(0, mindex);
                    //$.merge() 函数用于合并两个数组内容到第一个数组
                    arr = $.merge(arr, newarr);
                    $('.Cooldog_content li').each(function (i, e) {
                        $(e).removeClass().addClass(arr[i]);
                    })
                    index = myindex;
                    show();
                }
                else if (mindex < 0) {
                    //reverse() 方法用于颠倒数组中元素的顺序。
                    arr.reverse();
                    var oldarr = arr.splice(0, -mindex);
                    arr = $.merge(arr, oldarr);
                    arr.reverse();
                    $('.Cooldog_content li').each(function (i, e) {
                        $(e).removeClass().addClass(arr[i]);
                    })
                    index = myindex;
                    show();
                }
            })
        })

        //底部按钮高亮
        function show() {
            $('.buttons a').eq(index).addClass('color').siblings().removeClass('color');
        }
    })
</script>