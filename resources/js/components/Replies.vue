<template>
<div>
   <div v-for="reply of items" :key="reply.id">
      <reply :data="reply" @deleted="remove"></reply> 
   </div> 

   <paginator :dataSet="dataSet" @changed="fetch"></paginator>

   <p v-if="$parent.locked" class="text-muted mt-2 text-center">
       This thread has been locked. No more replies are allowed.
   </p>

   <new-reply @created="add" v-if="!$parent.locked"></new-reply>

</div>
</template>


<script>
import Reply from "./Reply";
import NewReply from "./NewReply";
import collection from '../mixins/collection';

export default {
    props: ['data'],
    components :{
        Reply,
        NewReply
    },

    mixins: [collection],

    data(){
        return {
            dataSet: false,
            items: []
        };
    },

    created(){

        this.fetch();
    },

    methods:{
        fetch(page){
            axios.get(this.url(page)).then(res => this.refresh(res));
        },

        url(page){
            
            if(!page){
                let query = location.search.match(/page=(\d+)/);

               page  = query ? query[1] : 1;
            }
            return `${location.pathname}/replies?page=${page}`;
        },

        refresh({data}){
            window.scrollTo(0,0);

            this.dataSet = data;

            this.items = data.data; 

        },

    }
}
</script>
