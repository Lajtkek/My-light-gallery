<template>
  <div>
    <Navbar />
    <!-- <hello-world /> -->
    <div v-if="action == 'upload'">
      <div class="upload-text">Upload</div>
      <div class="upload-input-wrapper">
        <v-file-input
          v-model="files"
          counter
          multiple
          show-size
          truncate-length="20"
        ></v-file-input>
        <v-card-actions class="justify-center">
          <v-btn color="blue" @click="upload" :disabled="files.length == 0"> Review and upload </v-btn>
        </v-card-actions>
      </div>
    </div>
    <div v-if="action == 'edit'">
      <div class="edit-text">File options</div>
      <v-card class="edit-image-wrapper">
        <v-tabs v-model="editData.tab" background-color="primary" dark>
          <v-tab v-for="file in editData.files" :key="file.name">
            {{ trimString(file.name, 20) }}
          </v-tab>
        </v-tabs>
        <v-tabs-items v-model="editData.tab">
          <v-tab-item v-for="file in editData.files" :key="file.name">
            <v-card flat>
              <v-card-text class="edit-image-card">
                <img :src="file.base64" class="edit-image">
              </v-card-text>
            </v-card>
          </v-tab-item>
        </v-tabs-items>
      </v-card>
    </div>
  </div>
</template>

<script lang="ts">
import Vue from "vue";
import Navbar from "../components/Navbar.vue";

export default Vue.extend({
  name: "Upload",
  components: {
    Navbar,
  },
  data: function () {
    return {
      action: "upload",
      files: [],
      editData: {
        tab: null,
        files: [
        ],
      }
    };
  },
  methods: {
    async upload() {
      for (const file of this.files) {
        let splitName = file.name.split(".")
        let extension = splitName[splitName.length - 1];

        if(extension != "gif" && extension != "jpg" && extension != "png"){
          console.warn(`${file.name} is not image. Support for other filetypes will be soon™`)
          continue;
        }

        let base64 = await this.getBase64(file);
        this.editData.files.push({
          name: file.name,
          base64
        })
      }
      this.action = "edit";
    },
    //TODO: try to do it in css? propably not it will fuck up the compoennt
    trimString(string, length) {
      return string.length > length ? string.substring(0, length) + '...' : string;
    },
    getBase64(file) {
      return new Promise((resolve, reject) => {
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function () {
          resolve(reader.result);
        };
        reader.onerror = function (error) {
          reject('Error: ' + error);
        };
      })
    }
  },
  mounted() {
    
  },
});
</script>

<style lang="less" scoped>
 @import '../assets/styles/main.less';

.upload-text {
  width: 100%;
  height: 30vh;
  .super-flex;

  font-size: 3rem;
}

.edit-text {
  width: 100%;
  height: 10vh;
  .super-flex;

  font-size: 3rem;
}

.upload-input-wrapper {
  width: 60%;
  min-width: 300px;
  max-width: 800px;
  margin: auto;
}

.edit-image-wrapper{
  width: 60%;
  min-width: 600px;
  margin: auto;

  .edit-image-card{
    .super-flex;
  }

  .edit-image{
    max-width: 100%;
    max-height: 600px;
    height: auto;
  }
}
</style>
