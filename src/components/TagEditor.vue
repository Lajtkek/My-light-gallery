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
              label="Tag name"
            ></v-text-field>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" sm="12" md="12">
            <v-btn @click="saveTag" class="float-right" :disabled="tag.name.length < 3">Save</v-btn>
          </v-col>
        </v-row>
      </v-container>
    </v-card>
  </v-dialog>
</template>

<script>
import store from '../store/store.js'

export default {
  name: "TagEditor",
  props: {
  },
  data: function () {
    return {
      showDialog: false,
      tag: null
    };
  },
  watch: {
    '$store.state.editedTag': function(){
      this.showDialog = store.state.editedTag != null;
      this.tag = store.state.editedTag;
    }
  },
  methods: {
    saveTag(){
      console.log(this.tag)
    }
  },
};
</script>

<style lang="less" scoped>
</style>
