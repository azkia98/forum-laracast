export default {
    data() {
        return {
            items: []
        }
    },
    methods: {


        add(item) {
            this.items.push(item);

            this.$emit('added');
        },
        remove(reply_id) {
            this.$emit('removed');

            this.items = this.items.filter(item => {
                return item.id != reply_id;
            });



            flash('Reply was deleted!');
        }
    }
}