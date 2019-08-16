<script>
import Replies from '../components/Replies';
import SubscribeButton from '../components/SubscribeButton';

export default {
    props : ['dataRepliesCount','dataLocked'],
    data(){
        return {
            repliesCount: this.dataRepliesCount,
            locked: this.dataLocked,
        };
    },
   components:{
       Replies,
       SubscribeButton
   } 
}
</script>
