<template>
  <div>
    <Navbar/>
    <!-- <hello-world /> -->
    <div class="upload">
      Upload
    </div>
    <div class="upload-input-wrapper">
      <v-file-input
          v-model="files"
          counter
          multiple
          show-size
          truncate-length="20"
      ></v-file-input>
      <v-card-actions class="justify-center">
        <v-btn color="blue" @click="upload">
            Review and upload
        </v-btn>
      </v-card-actions>
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
    data: function () {
      return {
        files: []
      }
    },
    methods: {
      upload(){
        this.files.forEach(file => {
          console.log(file)
          //this.getBase64(file);
        });
      },
      // getBase64(file) {
      //   var reader = new FileReader();
      //   reader.readAsDataURL(file);
      //   reader.onload = function () {
      //     console.log(reader.result);
      //   };
      //   reader.onerror = function (error) {
      //     console.log('Error: ', error);
      //   };
      // }
    },
    mounted(){
    }
  })
</script>

<style lang="less" scoped>
  .upload{
    width: 100%;
    height: 30vh;
    display: flex;
    justify-content: center;
    align-items: center;

    font-size: 3rem;
  }

  .upload-input-wrapper{
    width: 60%;
    min-width: 300px;
    max-width: 800px;
    margin: auto;
  }
</style>
