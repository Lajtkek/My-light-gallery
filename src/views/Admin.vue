<template>
  <div>
    <Navbar/>
    <div>
      Admin
    </div>
  </div>
</template>

<script>
import Vue from "vue";
import Navbar from "../components/Navbar.vue";

export default Vue.extend({
  name: "Admin",
  data: function () {
    return {
    };
  },
  components: {Navbar},
  methods: {},
});
</script>

<style lang="less" scoped>
</style>
