<template>
  <div class="form-group align-items-center d-flex">
    <input class type="file" name="avatar" accept="image/*" @change="onChange" />
  </div>
</template>


<script>
export default {
  methods: {
    onChange(e) {
      if (!e.target.files.length) return;

      let file = e.target.files[0];

      let src = URL.createObjectURL(file);

      this.$emit("loaded", { src, file });
    }
  }
};
</script>