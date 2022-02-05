<template>
  <div>
    <Navbar />
    <div class="gallery">Gallery</div>
    <v-container>
      <v-row>
        <v-col cols="2" sm="0" md="2"> </v-col>
        <v-col cols="3" sm="12" md="3" class="super-flex">
          <v-text-field
            v-model="filterData.filename"
            label="Filename"
            clearable
          ></v-text-field>
        </v-col>
        <v-col cols="4" sm="12" md="4" class="tag-filter-wrapper">
          <TagSelect v-model="filterData.values">
          </TagSelect>
        </v-col>
        <v-col cols="2" sm="12" md="2" class="apply-filter-btn-wrapper">
          <v-btn @click="reloadGallery">Apply filter</v-btn>
        </v-col>
        <v-col cols="1" sm="0" md="1"> </v-col>
      </v-row>
      <v-row> </v-row>
    </v-container>
    <div class="file-wrapper">
      <div v-for="file in files" :key="file.idFile" class="file">
        <div class="file-prev">
          <FilePreview :file="file"></FilePreview>
        </div>
        <div class="file-info">
          <div class="filename">
            {{ file.filename }}
          </div>
          <v-icon
            class="copy-permalink"
            @click="copyToClipboard(`${fileRootPath}/${file.permalink}`)"
            >mdi-content-copy
          </v-icon>
          <a :href="`/detail/${file.idFile}`" target="_blank" class="no-link">
            <v-icon class="copy-permalink">mdi-arrow-expand</v-icon>
          </a>
        </div>
      </div>
    </div>
    <div
      class="load-next-wrapper"
      v-if="canLoadMore && !allFilesFetched"
    >
      <v-btn color="red" :loading="pendingRequests.fetchFiles" @click="loadMore"
        >Load more</v-btn
      >
    </div>
  </div>
</template>

<script>
import Vue from "vue";
import Navbar from "../components/Navbar.vue";
import FilePreview from "../components/FilePreview.vue";
import TagSelect from "../components/TagSelect.vue";
import { copyToClipboard } from "../assets/js/common";

export default Vue.extend({
  name: "Gallery",
  components: {
    Navbar,
    FilePreview,
    TagSelect
  },
  watch: {
    "$store.state.fileTags": function () {
      this.filterData.tags = this.$store.state.fileTags;
    },
  },
  computed: {
    selectTags() {
      return this.filterData.tags.map((x) => ({
        color: x.color,
        text: x.name,
        value: x.idTag,
      }));
    },
  },
  data: function () {
    return {
      filterData: {
        tags: [],
        values: [],
        value: null,
        filename: "",
      },
      files: [],
      allFilesFetched: false,
      pendingRequests: {
        fetchFiles: false,
      },
      canLoadMore: false,
      fileRootPath: process.env.VUE_APP_IMAGE_ROOT,
    };
  },
  methods: {
    copyToClipboard,
    async loadMore() {
      this.pendingRequests.fetchFiles = true;

      let request = await Vue.prototype.get("files/getFiles", {
        offset: this.files.length,
        filename: this.filterData.filename,
        tags: this.filterData.values,
      });

      if (!request.error) {
        this.files.push(...request.data.files);
        if (request.data.files.length < request.data.limit) {
          this.allFilesFetched = true;
        }
      }

      this.pendingRequests.fetchFiles = false;
    },
    async reloadGallery() {
      let result = await Vue.prototype.get("files/getFiles", {
        filename: this.filterData.filename,
        tags: this.filterData.values,
      });
      if (!result.error) this.files = result.data.files;
      this.canLoadMore = result.data.files.length >= result.data.limit
    },
  },
  async mounted() {
    this.$store.commit("getFileTags");
    this.reloadGallery();
  },
});
</script>

<style lang="less" scoped>
@import "../assets/styles/main.less";
.gallery {
  .super-flex;

  width: 100%;
  height: 20vh;
  font-size: 3rem;
}
.input-selected-tag {
  margin: 4px !important;
}
.apply-filter-btn-wrapper {
  display: flex;
  justify-content: flex-start;
  align-items: center;
  margin-bottom: 20px;
}

.file-wrapper {
  .super-flex;
  flex-wrap: wrap;

  .file {
    text-decoration: none;
    margin: 20px;
    min-width: calc(300px - 20px);
    width: calc(20% - 20px);

    background: rgba(194, 194, 194, 0.671);
    border-radius: 10px;

    float: left;
    display: grid;
    grid-template-rows: 300px 60px;
    grid-template-areas:
      "file-prev"
      "bottom-bar";

    .file-prev {
      grid-area: file-prev;
    }

    .file-info {
      .super-flex;
      width: 100%;
      grid-area: bottom-bar;
      padding: 20px;
      overflow: hidden;
      text-decoration: none;
      color: black;
      .filename {
        .text-overflow-ddd;
        width: calc(100% - 80px);
      }
    }
  }
}

.load-next-wrapper {
  .super-flex;
  margin: 20px;
}
</style>