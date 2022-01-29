<template>
  <div>
    <Navbar />
    <div v-if="!loading">
      <div v-if="file" class="img-wrapper">
        <img :src="getFile(this.file.permalink)">
      </div>
      <div v-else class="error-wrapper">
        File not found :-(
      </div>
    </div>
    <div v-else class="loading">Loading</div>
  </div>
</template>

<script>
import Vue from "vue";
import Navbar from "../components/Navbar.vue";

export default Vue.extend({
  name: "Detail",
  data: function () {
    return {
      file: null,
      error: null,
      loading: true
    };
  },
  async mounted() {
    let request = await Vue.prototype.get("files/getFile", {
      idFile: this.$route.params.id,
    });

    if (!request.error) {
      this.file = request.data;
    }else{
      this.error = request.error;
    }
    this.loading = false;
  },
  components: { Navbar },
  methods: {},
});
</script>

<style lang="less" scoped>
@import "../assets/styles/main.less";
.img-wrapper {
  .super-flex;

  img {
    max-height: 80vh;
    width: auto;
  }
}

.loading{
  height: 30vh;
  width: 100%;
  .super-flex;
}

.error-wrapper{
  .super-flex;
}
</style>
