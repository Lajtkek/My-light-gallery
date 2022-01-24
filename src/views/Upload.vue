<template>
  <div>
    <Navbar/>
    <!-- <hello-world /> -->
    <div class="upload">
      Upload
    </div>
  </div>
</template>

<script lang="ts">
  import Vue from 'vue'
  import Navbar from '../components/Navbar.vue'

  export default Vue.extend({
    name: 'Upload',
    components: {
      Navbar,
    },
    methods: {
    },
    mounted(){
    }
  })
</script>

<style lang="less" scoped>
  .upload{
    width: 100%;
    height: 60vh;
    display: flex;
    justify-content: center;
    align-items: center;

    font-size: 3rem;
  }
</style>
