<template>
  <span>
    <v-icon class="copy-permalink">mdi-plus-box</v-icon>
    <v-icon class="copy-permalink">mdi-minus-box</v-icon>
  </span>
</template>

<script>
export default {
  name: "RatingComponent",
  props: [""],
  data: function () {
    return {
      // icon: "mdi-content-copy"
    };
  },
  methods: {
  },
};
</script>

<style lang="less" scoped>
</style>
