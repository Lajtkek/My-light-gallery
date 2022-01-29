<template>
  <div>
    <router-link to="/gallery">Explore light gallery</router-link>
  </div>
</template>

<script lang="ts">
  import Vue from 'vue'

  export default Vue.extend({
    name: 'Homepage',
    components: {
    },
    data: function(){
      return {
      }
    },
    methods: {
    },
    async mounted(){
    }
  })
</script>

<style lang="less" scoped>
  @import '../assets/styles/main.less';
  div{
    .super-flex;
    height: 90vh;
    width: 100%;
  }
</style>