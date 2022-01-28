<template>
  <div>
    <Navbar/>
    <div class="gallery">
      Gallery
    </div>
    <div v-for="file in files" :key="file.idFile">
      <!-- store the link for file in cfg -->
      <img :src="`http://localhost/resources/${file.permalink}.${file.extension}`">
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