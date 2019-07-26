<script>
export default {
    props: ['attributes'],
    data(){
        return {
            editing : false,
            body : this.attributes.body
        };
    },
    methods : {
        update(){
            axios.patch(`/replies/${this.attributes.id}`,{
                body: this.body
            }).then(res => flash('Your repyly has been updated'));

            this.editing = false;
        },
        destroy(){
            axios.delete('/replies/'+ this.attributes.id)
            .then(res => {
                $(this.$el).fadeOut(300,()=>{
                    flash('Your reply has been deleted');
                });
            });



        }
    }
}
</script>
