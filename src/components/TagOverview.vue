<template>
  <div>
    <v-data-table
      :headers="headers"
      :items="$store.state.fileTags"
      :items-per-page="50"
    >
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>File tags</v-toolbar-title>
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-spacer></v-spacer>
          <v-btn @click="createTag()">Create new tag</v-btn>
        </v-toolbar>
      </template>
      <template v-slot:item.color="{ item }">
        <v-chip :color="item.color" >
          {{ item.color }}
        </v-chip>
      </template>
    </v-data-table>
    <TagEditor></TagEditor>
  </div>
</template>

<script>
import store from "../store/store.js";
import TagEditor from "../components/TagEditor.vue";

export default {
  name: "TagOverview",
  components: { TagEditor },
  data: function () {
    return {
      headers: [
        { text: "ID", value: "idTag" },
        { text: "Name", value: "name" },
        { text: "Code", value: "code" },
        { text: "Color", value: "color" },
        { text: "Public", value: "isPublic" },
      ],
      tags: [],
    };
  },
  mounted() {
    store.commit("getFileTags");
  },
  methods: {
    createTag() {
      this.$store.commit("setEditedTag", {
        name: "",
        code: "",
        color: "#FF9393",
        isPublic: true,
      });
    },
  },
};
</script>

<style lang="less" scoped>
</style>
