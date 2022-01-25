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
          <v-btn color="blue" @click="upload" :disabled="files.length == 0">
            Review and upload
          </v-btn>
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
        <v-tabs-items v-model="editData.tab" class="edit-wrapper">
          <v-tab-item v-for="file in editData.files" :key="file.name">
            <v-card flat>
              <div class="edit-image-card">
                <img :src="file.base64" class="edit-image" />
              </div>
              <div class="edit-image-info-card">
                <v-form>
                  <v-container>
                    <v-row>
                      <v-col cols="12" sm="6" md="6">
                        <v-text-field
                          label="Name"
                          v-model="file.newName"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" sm="6" md="6">
                        <v-autocomplete 
                          v-if="editData.hackBool"
                          clearable
                          v-model="editData.tagToAdd"
                          :items="selectableTags(file)"
                          @change="addTag(file)" 
                        ></v-autocomplete>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="12" sm="6" md="6">
                        <v-textarea
                          v-model="file.description"
                          placeholder="Description"
                          background-color="grey lighten-2"
                          solo
                        ></v-textarea>
                      </v-col>
                      <v-col cols="12" sm="6" md="6">
                        <v-chip
                          v-for="tag in file.tags" :key="tag.idTag"
                          close
                          close-icon="mdi-delete"
                          :color="tag.color"
                          @click:close="removeTag(file,tag)"
                        >{{tag.name}}</v-chip>
                      </v-col>
                    </v-row>
                  </v-container>
                </v-form>
              </div>
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
//import {clone} from "../assets/js/common";

export default Vue.extend({
  name: "Upload",
  components: {
    Navbar,
  },
  watch: {
    '$store.state.fileTags': function(){
      this.editData.tags = this.$store.state.fileTags
    }
  },
  data: function () {
    return {
      action: "upload",
      files: [],
      editData: {
        hackBool: true,
        tagToAdd: null,
        tab: null,
        files: [],
        tags: this.$store.state.fileTags
      },
    };
  },
  methods: {
    async upload() {
      for (const file of this.files) {
        let splitName = file.name.split(".");
        let extension = splitName[splitName.length - 1];

        if (extension != "gif" && extension != "jpg" && extension != "png") {
          console.warn(
            `${file.name} is not image. Support for other filetypes will be soon™`
          );
          continue;
        }

        let base64 = await this.getBase64(file);
        this.editData.files.push({
          name: file.name,
          newName: file.name.replace(`.${extension}`, ""),
          description: "",
          tags: [],
          file,
          base64,
        });
      }
      this.$store.commit('getFileTags');
      this.action = "edit";
    },
    //TODO: try to do it in css? propably not it will fuck up the compoennt
    trimString(string, length) {
      return string.length > length
        ? string.substring(0, length) + "..."
        : string;
    },
    getBase64(file) {
      return new Promise((resolve, reject) => {
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function () {
          resolve(reader.result);
        };
        reader.onerror = function (error) {
          reject("Error: " + error);
        };
      });
    },
    addTag(file){
      //Projistotu kopíruju, asi nebude potřeba idk
      file.tags.push({...this.editData.tags.find(x => x.idTag == this.editData.tagToAdd)});
      this.editData.tagToAdd = null;
      // TODO: fix? mby not nessesary https://github.com/vuetifyjs/vuetify/issues/10765
      this.editData.hackBool = false;
      this.$nextTick(() => {
        this.editData.hackBool = true;
      })
    },
    removeTag(file,tag){
      file.tags = file.tags.filter(x => x.idTag != tag.idTag);
    },
    selectableTags(file){
      return this.editData.tags.filter(tag => !file.tags.some(x => x.idTag == tag.idTag)).map(x => ({ text: x.name,  value: x.idTag}))
    }
  },
  async mounted() {
    // let result = await Vue.prototype.get("tags/getAllTags");
    // if(result.success){
    //   this.editData.tags = result.data;
    // }
  },
});
</script>

<style lang="less" scoped>
@import "../assets/styles/main.less";

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

.edit-wrapper {
  margin-bottom: 20px;
}

.edit-image-wrapper {
  width: 60%;
  min-width: 600px;
  margin: auto;

  .edit-image-card {
    .super-flex;
    padding: 10px;
  }

  .edit-image-info-card {
    min-height: 100px;
  }

  .edit-image {
    max-width: 100%;
    max-height: 600px;
    height: auto;
  }
}
</style>
