<template>
    <div class="alert alert-fix  m-0" :class="`alert-${level}`" role="alert" v-show="show">
        <strong>Success!</strong> {{ body }}
    </div>
</template>

<script>
    export default {
        props: ['message'],
        data(){
            return {
                body : this.message,
                show : false,
                level : 'success'
            }
        },
        created(){
            if(this.message){
                this.flash(this.message);
                this.hide();
            }

            window.events.$on('flash',data =>{
                this.flash(data);
                this.hide();
            });


        },
        methods : {
            flash({ message , level}){
                this.body = message; 
                this.level = level;
               this.show = true; 
            },
            hide(){
                setTimeout(() => {
                    this.show = false;
                }, 3000);
            }
        }
    }
</script>

<style lang="css">
.alert-fix{
   position: fixed; 
   right: 20px;
  bottom: 20px;
}
</style>
