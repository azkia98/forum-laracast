<template>
  <button :class="classes" type="button" @click="subscribe">Subscribe</button>
</template>


<script>
export default {
  props: ["active"],
  methods: {
    subscribe() {
      let requestType = this.active ? "delete" : "post";

      axios[requestType](location.pathname + "/subscriptions").then(res => {
        this.active = !this.active;

      });
    }
  },
  computed: {
    classes() {
      return ["btn", this.active ? "btn-secondary" : "btn-outline-secondary"];
    }
  }
};
</script>
