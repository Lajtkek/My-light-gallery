<template>
  <div>
    <Navbar />
    <div v-if="!loading">
      <div v-if="file" class="img-wrapper">
        <v-img :src="getFile(this.file.permalink)" max-height="80vh" contain></v-img>
        <div class="tag-wrapper">
          <v-chip
              v-for="tag in file.tags"
              :key="tag.idTag"
              :color="tag.color">{{ tag.name }}</v-chip>
        </div>
      </div>
      <div v-else class="error-wrapper">
        File not found :-(
      </div>
    </div>
    <div v-else class="loading">
      <v-progress-circular
        :size="70"
        :width="7"
        indeterminate
        color="primary"
      ></v-progress-circular>
    </div>
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
.img-wrapper{
  .super-flex;
  flex-flow: column;

  .tag-wrapper{
    margin: 30px;
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
