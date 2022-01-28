<template>
  <v-dialog v-model="showDialog" max-width="500px">
    <v-card v-if="tag != null">
      <v-container>
        <v-row>
          <v-col cols="12" sm="6" md="4">
            <v-text-field
              v-model="tag.name"
              label="Dessert name"
            ></v-text-field>
          </v-col>
          <v-col cols="12" sm="6" md="4">
            <v-text-field
              label="Calories"
            ></v-text-field>
          </v-col>
          <v-col cols="12" sm="6" md="4">
            <v-text-field
              label="Fat (g)"
            ></v-text-field>
          </v-col>
          <v-col cols="12" sm="6" md="4">
            <v-text-field
              label="Carbs (g)"
            ></v-text-field>
          </v-col>
          <v-col cols="12" sm="6" md="4">
            <v-text-field
              label="Protein (g)"
            ></v-text-field>
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
  computed: {
  },
  mounted() {},
  methods: {},
};
</script>

<style lang="less" scoped>
</style>
