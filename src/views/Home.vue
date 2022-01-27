<template>
  <div>
    <Navbar/>
    <!-- <hello-world /> -->
    <div class="gallery">
      Gallery
    </div>
    <div v-for="file in files" :key="file.idFile">
      <img :src="`http://localhost/api/files/getFile.php?permalink=${file.permalink}`">
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
</style>