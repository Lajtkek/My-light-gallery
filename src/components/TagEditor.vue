<template>
  <v-dialog v-model="showDialog" max-width="500px">
    <v-card v-if="tag != null">
      <v-container>
        <v-row>
          <v-col cols="6" sm="6" md="6">
            <v-color-picker
              mode="hexa"
              dot-size="25"
              swatches-max-height="200"
              v-model="tag.color"
            ></v-color-picker>
          </v-col>
          <v-col cols="6" sm="6" md="6">
            <v-text-field
              v-model="tag.name"
              @input="updateTagCode"
              label="Tag name"
            ></v-text-field>
             <v-text-field
              v-model="tag.code"
              label="Tag code"
            ></v-text-field>
            <v-checkbox
              v-model="tag.isPublic"
              label="Public"
            ></v-checkbox>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" sm="12" md="12">
            <TagSelect v-model="tag.tags" :preselectedTags="tag.tags" :hiddenTags="tag.idTag ? [tag.idTag] : []"></TagSelect>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" sm="12" md="12">
            <v-btn :loading="pendingRequests.saveTag" @click="saveTag" class="float-right" :disabled="tag.name.length < 3 || tag.code.length < 3">Save</v-btn>
          </v-col>
        </v-row>
      </v-container>
    </v-card>
  </v-dialog>
</template>

<script>
import TagSelect from "../components/TagSelect.vue"
import Vue from "vue";
import store from '../store/store.js'

export default {
  name: "TagEditor",
  components: {
    TagSelect
  },
  props: {
  },
  data: function () {
    return {
      showDialog: false,
      tag: null,
      pendingRequests: {
        saveTag: false,
      }
    };
  },
  watch: {
    '$store.state.editedTag': function(){
      this.showDialog = store.state.editedTag != null;
      this.tag = store.state.editedTag;
    }
  },
  methods: {
    updateTagCode(){
      let code = this.tag.name.replaceAll(" ", "_").toUpperCase();
      this.tag.code = code;
    },
    async saveTag(){
      this.pendingRequests.saveTag = true;
      let {idTag, name, code, color, isPublic, tags} = {...this.tag};

      let result;
      if(this.tag.idTag)
        result = await Vue.prototype.put("tags/tagIU", {idTag, name, code, color, isPublic, tags});
      else
        result = await Vue.prototype.post("tags/tagIU", {name, code, color, isPublic, tags});

      if(!result.error){
        this.$store.commit('fileTagIU', result.data);
        this.$store.commit('setEditedTag', null);
      }else{
        console.warn(`Error occured when saving tag error code: ${result.error}`);
      } 

      this.pendingRequests.saveTag = false;
    }
  },
};
</script>

<style lang="less" scoped>
</style>
