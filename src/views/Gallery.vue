<template>
  <div>
    <Navbar/>
    <div class="gallery">
      Gallery
    </div>
    <div class="file-wrapper">
      <div v-for="file in files" :key="file.idFile" class="file">
          <!-- store the link for file in cfg -->
          <div class="file-prev">
            <a :href="`/detail/${file.idFile}`" target="_blank">
              <img :src="getFile(file.permalink)">
            </a>
          </div>
          <div class="file-info">
            <div class="filename">
              {{ file.filename }} 
            </div>
            <v-icon class="copy-permalink" @click="copyToClipboard(`${fileRootPath}/${file.permalink}`)">mdi-content-copy</v-icon>
          </div>
      </div>
    </div>
    <div class="load-next-wrapper" v-if="files.length >= filesPerRequest && !allFilesFetched">
      <v-btn color="red" :loading="pendingRequests.fetchFiles" @click="loadMore">Load more</v-btn>
    </div>
  </div>
</template>

<script lang="ts">
  import Vue from 'vue'
  import Navbar from '../components/Navbar.vue'
  import { copyToClipboard } from "../assets/js/common"
  export default Vue.extend({
    name: 'Gallery',
    components: {
      Navbar,
    },
    data: function(){
      return {
        files: [],
        allFilesFetched: false,
        pendingRequests: {
          fetchFiles: false,
        },
        filesPerRequest: process.env.VUE_APP_FILES_PER_REQUEST,
        fileRootPath: process.env.VUE_APP_IMAGE_ROOT
      }
    },
    methods: {
      copyToClipboard,
      async loadMore(){
        this.pendingRequests.fetchFiles = true;
        let request = await Vue.prototype.get("files/getFiles", {offset: this.files.length });

        if(!request.error){
          this.files.push(...request.data);
          if(request.data.length < this.filesPerRequest){
            this.allFilesFetched = true;
          }
        }

        this.pendingRequests.fetchFiles = false;
      }
    },
    async mounted(){
      let result = await Vue.prototype.get("files/getFiles");

      if(!result.error)
        this.files = result.data;
    }
  })
</script>

<style lang="less" scoped>
  @import '../assets/styles/main.less';
  .gallery{
    .super-flex;

    width: 100%;
    height: 20vh;
    font-size: 3rem;
  }

  .file-wrapper{
    .super-flex;
    flex-wrap: wrap;

    .file{
      text-decoration: none;
      margin: 20px;
      min-width: calc(300px - 20px);
      width: calc(20% - 20px);

      background: rgba(194, 194, 194, 0.671);
      border-radius: 10px;

      float: left;
      display:grid;
      grid-template-rows: 300px 60px;
      grid-template-areas: 
      "file-prev"
      "bottom-bar";
      
      .file-prev{
        grid-area: file-prev;

        a{
          .super-flex;
          height: 100%;
          width: 100%;
        }

        img{
          max-width: calc(100% - 20px);
          max-height: calc(100% - 20px);
        }
      }
      
      .file-info{
        .super-flex;
        width: 100%;
        grid-area: bottom-bar;
        padding: 20px;
        overflow: hidden;
        text-decoration: none;
        color: black; 
        .filename{
          .text-overflow-ddd;
          width: calc(100% - 48px);
        }
      }
    }
  }

  .load-next-wrapper{
    .super-flex;
    margin: 20px;
  }
</style>