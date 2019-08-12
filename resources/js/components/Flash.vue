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
                console.log('fadf')
                this.flash();
                this.hide();
            }

            window.events.$on('flash',data =>{
                this.flash(data);
                this.hide();
            });


        },
        methods : {
            flash(data){
                if(data){
                    this.body = data.message; 
                    this.level = data.level;
                }
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
