<!--[if lt IE 9]>
<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
<![endif]-->
<script>
    $(function () {
        var url                 =   '{{url('/')}}';
        $('.search-btn').on('click',function () {

            var q                 =   $('#keywords').val() || $('#keywords-fixed').val();
            if(q && q!==''){
                location.href       =   url+'/goods?q='+q;
            }
        });
        $('#keywords').keyup(function (event) {
            if(event.which===13){
                const q                 =   $('#keywords').val();
                if(q && q!==''){
                    location.href       =   url+'/goods?q='+q;
                }
            }
        });
        $('#keywords-fixed').keyup(function (event) {
            console.log(event)
            if(event.which===13){
                const q                 =   $('#keywords-fixed').val();
                if(q && q!==''){
                    location.href       =   url+'/goods?q='+q;
                }
            }
        });

        $(window).scroll(function(e){
            p = $(this).scrollTop();
            if(p < 160){
                $(".search-bar-fixed").slideUp();
            }else{
                $(".search-bar-fixed").slideDown();
            }

            setTimeout(function(){t = p;},0);
        });
    });

    function scrollTop() {
        scrollToptimer = setInterval(function () {

            let top = document.body.scrollTop || document.documentElement.scrollTop;
            let speed = top / 4;
            if (document.body.scrollTop !== 0) {
                document.body.scrollTop -= speed;
            } else {
                document.documentElement.scrollTop -= speed;
            }
            if (top === 0) {
                clearInterval(scrollToptimer);
            }
        }, 30);
    }

</script>
<!-- WPA start -->
<script id="qd2852155350679e8f5c2e9c8f5b65f3c4681166637d" src="https://wp.qiye.qq.com/qidian/2852155350/679e8f5c2e9c8f5b65f3c4681166637d" charset="utf-8" async defer></script>
<!-- WPA end -->
@yield('script')