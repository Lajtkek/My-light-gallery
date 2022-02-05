<template>
  <div>
    <Navbar />
    <div v-if="!loading">
      <div v-if="file" class="img-wrapper">
        <div class="file">
          <FilePreview :file="file"></FilePreview>
        </div>
        <div class="filename-wrapper">
          {{ file.filename }} <CopyLink :link="`${fileRootPath}/${file.permalink}`"></CopyLink>
        </div>
        <div class="tag-wrapper">
          <v-chip
              v-for="tag in file.tags"
              :key="tag.idTag"
              :color="tag.color">{{ tag.name }}</v-chip>
        </div>
        <div class="description" v-if="file.description">
          {{file.description}}
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
import CopyLink from "../components/CopyLink.vue";

export default Vue.extend({
  name: "Detail",
  components: {
    Navbar,
    FilePreview,
    CopyLink
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
  },
});
</script>

<style lang="less" scoped>
@import "../assets/styles/main.less";
.img-wrapper{
  .super-flex;
  flex-flow: column;
  width: 80%;
  margin: auto;
  // background: #f6f6f6;
  border-radius: 10px;
  margin-top: 30px;
  margin-bottom: 30px;

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

  .description{
    width: 60%;
    margin: 30px;
    background: rgba(1,1,1,0.1);
    padding: 30px;
    border-radius: 10px;
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
