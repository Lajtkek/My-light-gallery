<template>
  <v-dialog v-model="tag" max-width="500px">
    <v-card>
      {{ tag }}
    </v-card>
  </v-dialog>
</template>

<script>
  //import store from '../store/store.js'

  export default {
    name: 'TagEditor',
    props: {
      tag: {
        type: Object
      }
    },
    data: function(){
      return {
        //tag: null
      }
    },
  //  show:function(newValue){
  //     if(!newValue){
  //       this.tag = null;
  //     }
  //   },
    mounted(){
    },
    methods: {
    },
  }
</script>

<style lang="less" scoped>
</style>
