<script>
import Replies from "../components/Replies";
import SubscribeButton from "../components/SubscribeButton";

export default {
    props: ["thread"],
    data() {
        return {
            repliesCount: this.thread.replies_count,
            locked: this.thread.locked
        };
    },
    components: {
        Replies,
        SubscribeButton
    },
    methods: {
        lockToggle() {

            axios[this.locked ? 'delete' : 'post'](`/locked_threads/${thread.slug}`);

            this.locked = ! this.locked;
        }
    }
};
</script>
