<script>
    var lang = '{{request()->lang}}';
    if(lang !==''){
        localStorage.setItem("lang",lang);
    }
</script>