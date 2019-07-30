<template>
  <div v-if="shouldPaginate">
    <ul class="pagination justify-content-center">
      <li class="page-item" v-show="prevUrl" @click.prevent="page--">
        <a class="page-link" href="#" tabindex="-1">Previous</a>
      </li>
      <li class="page-item" v-show="nextUrl" @click.prevent="page++">
        <a class="page-link" href="#">Next</a>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  props: ["dataSet"],
  data() {
    return {
      page: 1,
      prevUrl: false,
      nextUrl: ""
    };
  },
  watch: {
    dataSet() {
      console.log(this.dataSet.next_page_url);
      this.page = this.dataSet.current_page;
      this.prevUrl = this.dataSet.prev_page_url;
      this.nextUrl = this.dataSet.next_page_url;
    },

    page(){
      this.broadcast().updateUrl();
    }
  },
  computed: {
    shouldPaginate() {
      return !!this.prevUrl || !!this.nextUrl;
    }
  },


  methods:{
    broadcast(){
      this.$emit('changed',this.page);

      return this;
    },

    updateUrl(){
      history.pushState(null,null,'?page='+this.page);
    }
  }
};
</script>
