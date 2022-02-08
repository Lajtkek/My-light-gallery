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
        <template v-slot:item.tags="{ item }">
        {{item.tags.join(",")}}
        </template>
      <template v-slot:item.edit="{ item }">
        <v-btn @click="editTag(item)">Edit</v-btn>
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
        { text: "Color", value: "color", width: "60px" },
        { text: "Public", value: "isPublic", width: "100px" },
        { text: "Tags", value: "tags" },
        { text: "Edit", value: "edit", width: "1%" },
      ],
      tags: [],
    };
  },
  mounted() {
    store.commit("getFileTags", 1);
  },
  methods: {
    editTag(tag){
      this.$store.commit("setEditedTag", {
        ...tag
      });
    },
    createTag() {
      this.$store.commit("setEditedTag", {
        name: "",
        code: "",
        color: `#${Math.floor(Math.random()*16777215).toString(16)}`,
        isPublic: true,
        tags: []
      });
    },
  },
};
</script>

<style lang="less" scoped>
</style>
