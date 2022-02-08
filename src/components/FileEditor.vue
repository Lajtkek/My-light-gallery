<template>
  <v-dialog v-model="showDialog" max-width="700px">
    <v-card v-if="file">
      <v-container>
        <v-row>
          <v-col cols="12" sm="12" md="12">
             <v-text-field
              v-model="file.filename"
              label="Image name"
            ></v-text-field>
          </v-col>
         
        </v-row>
        <v-row>
           <v-col cols="12" sm="12" md="12">
            <TagSelect v-model="file.tags" :preselectedTags="file.tags" ref="tagSelect"></TagSelect>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" sm="12" md="12">
          <v-textarea
            v-model="file.description"
            placeholder="Description"
            background-color="grey lighten-2"
            solo
          ></v-textarea>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" sm="12" md="12">
            <v-btn @click="save" :loading="savingFile" class="pull-right">Save</v-btn>
          </v-col>
        </v-row>
      </v-container>
    </v-card>
  </v-dialog>
</template>

<script>
// import Vue from "vue";
// import store from '../store/store.js'
import TagSelect from "../components/TagSelect.vue"

export default {
  name: "FileEditor",
  components: {
    TagSelect
  },
  props: ["onFileSaved"],
  data: function () {
    return {
      showDialog: false,
      file: {},
      savingFile: false
    };
  },
  watch: {
  },
  mounted(){
  },
  methods: {
    async save(){
      this.savingFile = true;

      let result = await this.put("files/editFile", {
          ...this.file,
          filename: this.file.filename +"."+ this.file.extension,
          tags: this.file.tags.map(x => ({ idTag: x}))
      });

      if(!result.error){
        this.showSuccessTooltip("File was updated.")
        this.onFileSaved?.();
        this.showDialog = false;
      }else{
        console.log(result.error)
      }

      this.savingFile = false;
    }
  },
};
</script>

<style lang="less" scoped>
@import "../assets/styles/main.less";
</style>
