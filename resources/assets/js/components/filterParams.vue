<template>
    <div class="filters-params">
         <span v-if="brand" class="filter-param">
            {{brand.name}}:{{brand.value}}<span @click="handleBrandClick()">X</span>
        </span>
        <span v-for="filter in attrs" class="filter-param">
            {{filter.name}}:{{filter.value}}<span @click="handleAttrClick(filter)">X</span>
        </span>
    </div>
</template>

<script>
    export default {
        name: "filter-params",
        props:{
            params:{
                type:Object,
                default:{}
            },
            url:''
        },
        data(){
          return {
              filters:[],
              brand:'',
              attrs:[]
          }
        },
        created(){
            this.setFilters();
        },
        methods:{
            setFilters(){
                const array             =   [];
                if(this.params.brand){
                    const brand             =   this.params.brand.split(':');
                    this.brand              =   {name:this.$t('goods.brand'),value:brand[1]};
                }else {
                    this.params.brand = ''
                }


                if(this.params.attr){
                    const attrs             =   this.params.attr.split(',');
                    console.log(attrs);
                    attrs.forEach(function (item) {
                        var attr            =   item.split(':');
                        array.push({name:attr[0],value:attr[1]})
                    })
                }else {
                    this.params.attr = '';
                }

                this.attrs              =   array;
            },
            handleBrandClick(){
                console.log(this.params.attr);
                location.href           =   this.url+'?attr='+this.params.attr+'&sort='+this.params.sort;
            },
            handleAttrClick(filter){
                var str                 =   this.params.attr;
                if(this.attrs && filter){
                    str                 =   ''
                    this.attrs.splice(this.attrs.indexOf(filter),1);
                    this.attrs.forEach(function (item) {
                        var attr      =   item.name+':'+item.value;
                        str             +=  attr+',';
                    })
                    if(str.length > 0){
                        str             =   str.substring(0,str.length-1)
                    }
                }
               location.href           =   this.url+'?brand='+this.params.brand+'&sort='+this.params.sort+'&attr='+str;
            }
        }
    }
</script>

<style scoped lang="scss">
    .filters-params{
        background-color: #f9f9f9;
        padding: 15px;
    }
    .filter-param{

        color: #35a0fc;
        display: inline-block;
        padding: 5px 10px;
        span{
            color:#333;
            cursor: pointer;
        }
    }
</style>