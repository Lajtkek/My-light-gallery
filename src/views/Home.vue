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
          <img :src="getFile(`${file.permalink}.${file.extension}`)">
        </div>
        <div class="file-info">
          {{ file.filename }}
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
  import Vue from 'vue'
  import Navbar from '../components/Navbar.vue'

  export default Vue.extend({
    name: 'Home',
    components: {
      Navbar,
    },
    data: function(){
      return {
        files: []
      }
    },
    methods: {
    },
    async mounted(){
      this.files = await Vue.prototype.get("files/getFiles");
      this.files = this.files.data
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
        .super-flex;
        grid-area: file-prev;

        img{
          max-width: calc(100% - 20px);
          max-height: calc(100% - 20px);
        }
      }

      .file-info{
        width: 100%;
        grid-area: bottom-bar;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        text-align: center;
        padding: 20px;
      }
    }
  }
</style>