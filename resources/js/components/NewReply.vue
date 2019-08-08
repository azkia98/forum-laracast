<template>
  <div >
    <div class="form-group" v-if="signedIn">
      <textarea
        name="body"
        placeholder="Have something to say?"
        rows="5"
        class="form-control"
        v-model="body"
        id="newReply"
        required
      ></textarea>
      <button type="submit" class="btn btn-secondary mt-1" @click="addReply">Post</button>
    </div>
    <p class="text-center" v-if="!signedIn">
      Please
      <a href="/login">sign in</a> to participate in this discussion.
    </p>
  </div>
</template>


<script>
import 'jquery.caret';
import 'at.js';


export default {
  data() {
    return {
      body: null
    };
  },
  methods: {
    addReply() {
      axios.post(`${location.pathname}/replies`, { body: this.body })
      .then(response => {
        this.body = "";
        flash("Your reply has been posted.");

        this.$emit("created", response.data);
      })
      .catch(err => {
        flash(err.response.data,'danger');
      });
    }
  },
  mounted(){
    $('#newReply').atwho({
      at: '@',
      delay: 600,
      callbacks:{
        remoteFilter: function(query,callback){
          $.getJSON('/api/users',{name:query},function(usernames){
            console.log(usernames);
            callback(usernames)
          });
        }  
      }
    });
  },
  computed: {
    signedIn() {
      return window.App.signedIn;
    }
  }
};
</script>
