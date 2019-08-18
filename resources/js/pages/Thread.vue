<script>
import Replies from "../components/Replies";
import SubscribeButton from "../components/SubscribeButton";

export default {
    props: ["thread"],
    data() {
        return {
            repliesCount: this.thread.replies_count,
            locked: this.thread.locked,
            title: this.thread.title,
            body: this.thread.body,
            form: {},
            editing: false
        };
    },
    components: {
        Replies,
        SubscribeButton
    },
    created(){
        this.resetForm();
    },
    methods: {
        lockToggle() {

            axios[this.locked ? 'delete' : 'post'](`/locked_threads/${thread.slug}`);

            this.locked = ! this.locked;
        },
        resetForm(){
            this.form= {
                title: this.thread.title,
                body: this.thread.body
            };

            this.editing = false;
        },
        update(){
            let uri = `/threads/${this.thread.channel.slug}/${this.thread.slug}`;
            axios.patch(uri,this.form).then(res => {
                flash('Your thread has been updated.');

                this.title = this.form.title;
                this.body = this.form.body;

                this.resetForm();
            });
        }
    }
};
</script>
