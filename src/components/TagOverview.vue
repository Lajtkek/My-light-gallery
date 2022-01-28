<template>
  <div>
    <v-data-table
    :headers="headers"
    :items="$store.state.fileTags"
    :items-per-page="50"
    >
    <template v-slot:top>
      <v-toolbar
        flat
      >
        <v-toolbar-title>File tags</v-toolbar-title>
        <v-divider
          class="mx-4"
          inset
          vertical
        ></v-divider>
        <v-spacer></v-spacer>
        <v-btn @click="createTag()">Add new tag</v-btn>
      </v-toolbar>
    </template>
    </v-data-table>
    <TagEditor :tag="selectedTag"></TagEditor>
  </div>
</template>

<script>
  import store from '../store/store.js'
  import TagEditor from "../components/TagEditor.vue";

  export default {
    name: 'TagOverview',
    components: { TagEditor },
    data: function(){
      return {
        headers: [
          { text: 'ID', value: 'idTag' },
          { text: 'Name', value: 'name' },
          { text: 'Color', value: 'color' },
        ],
        tags: [],
        selectedTag: null,
      }
    },
    mounted(){
      store.commit("getFileTags");
    },
    methods: {
      createTag(){
        this.$store.commit("setEditedTag", {
          test: 123,
          name: "",
        })
      }
    },
  }
</script>

<style lang="less" scoped>
</style>
