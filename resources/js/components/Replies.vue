<template>
<div>
   <div v-for="reply of items">
      <reply :data="reply" @deleted="remove"></reply> 
   </div> 
</div>
</template>


<script>
import Reply from "./Reply";
export default {
    props: ['data'],
    components :{
        Reply
    },
    data(){
        return {
            items: null
        };
    },
    mounted(){
        this.items  = this.data;
    },
    methods:{
        remove(reply_id){
            this.$emit('removed');
            
            this.items = this.items.filter(item=>{
                return item.id != reply_id;
            });



            flash('Reply was deleted!');
        }
    }
}
</script>
