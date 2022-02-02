<template>
  <div>
    <Navbar />
    <div v-if="!loading">
      <div v-if="file" class="img-wrapper">
        <div class="file">
          <FilePreview :file="file"></FilePreview>
        </div>
        <div class="filename-wrapper">
          {{ file.filename }} <v-icon class="copy-permalink" @click="copyToClipboard(`${fileRootPath}/${file.permalink}`)">mdi-content-copy</v-icon>
        </div>
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
import FilePreview from "../components/FilePreview.vue";
import { copyToClipboard } from "../assets/js/common";

export default Vue.extend({
  name: "Detail",
  components: {
    Navbar,
    FilePreview
  },
  data: function () {
    return {
      file: null,
      error: null,
      loading: true,
      fileRootPath: process.env.VUE_APP_IMAGE_ROOT
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
  methods: {
    copyToClipboard
  },
});
</script>

<style lang="less" scoped>
@import "../assets/styles/main.less";
.img-wrapper{
  .super-flex;
  flex-flow: column;

  .filename-wrapper{
    .text-overflow-ddd;
    margin: 10px;
    width: 60%;
  }

  .tag-wrapper{
    margin: 10px;
  }

  .copy-permalink{
    cursor: pointer;
  }

  .file{
    max-width: 80vh;
    height: 60vh;
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
