<template>
    <div class="alert alert-primary alert-fix m-0" role="alert" v-show="show">
        <strong>Success!</strong> {{ body }}
    </div>
</template>

<script>
    export default {
        props: ['message'],
        data(){
            return {
                body : this.message,
                show : false
            }
        },
        created(){
            if(this.message){
                this.flash(this.message);
                this.hide();
            }

            window.events.$on('flash',message =>{
                this.flash(message);
                this.hide();
            });


        },
        methods : {
            flash(message){
                this.body = message; 
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
