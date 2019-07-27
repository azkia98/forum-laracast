<template>
    <div class="d-flex align-items-center">
        <button  type="submit" :class="classes" @click="toggle">
            <span v-text="count"></span>
            <span class="glyphicon glyphicon-heart">Favorites</span>
        </button>
    </div>
</template>


<script>
export default {
    props:['reply'],
    data(){
        return {
            count : this.reply.favoritesCount,
            active : this.reply.isFavorited
        }
    },
    methods:{
        toggle(){
           this.active ? this.destroy() : this.create();
        },
        destroy(){
            axios.delete(this.endpoint);
            this.active = false;
            this.count--;
        },
        create(){
            axios.post(this.endpoint);
            this.active = true;
            this.count++;
        }
    },
    computed: {
        classes(){
            return ['btn','btn-sm', this.active ? 'btn-secondary' : 'btn-outline-secondary']
        },
        endpoint(){
            return `/replies/${this.reply.id}/favorites`;
        }
    },
}
</script>