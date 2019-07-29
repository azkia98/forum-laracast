<template>
<div>
   <div v-for="reply of items">
      <reply :data="reply" @deleted="remove"></reply> 
   </div> 

   <new-reply @created="add" endpoint="/threads/nihil/1/replies"></new-reply>
</div>
</template>


<script>
import Reply from "./Reply";
import NewReply from "./NewReply";
export default {
    props: ['data'],
    components :{
        Reply,
        NewReply
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
        add(reply){
            this.items.push(reply);

            this.$emit('added');
        },
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
