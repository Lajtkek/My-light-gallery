<template>
  <div>
    <v-data-table
    :headers="headers"
    :items="$store.state.fileTags"
    :items-per-page="5"
    ></v-data-table>
  </div>
</template>

<script>
  import store from '../store/store.js'

  export default {
    name: 'TagEditor',
    data: function(){
      return {
        headers: [
          { text: 'ID', value: 'idTag' },
          { text: 'Name', value: 'name' },
          { text: 'Color', value: 'color' },
        ],
        tags: [] 
      }
    },
    watch:{
    },
    mounted(){
      store.commit("getFileTags");
    },
    methods: {
    },
  }
</script>

<style lang="less" scoped>
</style>
