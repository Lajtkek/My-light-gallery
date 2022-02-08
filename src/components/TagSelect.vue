<template>
  <v-autocomplete
    @input="serachInput = ''"
    :search-input.sync="serachInput"
    multiple
    clearable
    v-model="value"
    :items="selectableTags"
    >
    <template v-slot:selection="data">
      <v-chip
        v-if="showChips"
        v-bind="data.attrs"
        :input-value="data.selected"
        close
        :color="data.item.color"
        @click="data.select"
        @click:close="remove(data.item)"
      >
        {{ data.item.text }}
      </v-chip>
    </template>
  </v-autocomplete>
</template>

<script>
export default {
  name: "TagSelect",
  props: {
    showChips: {
      type: Boolean,
      default: true,
    },
    preselectTags: {
      type: Boolean,
      default: false,
    },
    preselectedTags: {
      type: Array,
      default: function(){
        return []
      }
    },
    hiddenTags: {
      type: Array,
      default: function(){
        return []
      }
    }
  },
  data: function(){
    return {
      serachInput: "",
      selectableTags: [],
      value: [],
    }
  },
  watch: {
    '$store.state.fileTags': function(){
      this.refreshTags();
    },
    'value': function(new_val,old_val){
      if(this.preselectTags && new_val.length > old_val.length){
        let addedTagId = new_val.find(x => !old_val.includes(x))[0];
        let addedTag = this.$store.state.fileTags.find(x => x.idTag == addedTagId);
        this.value = [...this.value, ...addedTag.tags.filter(c => !this.value.includes(c)) ];
      }
      this.$emit('input', new_val);
    }
  },
  mounted() {
    this.value = this.preselectedTags;
    this.refreshTags();
  },
  methods: {
    refreshTags(){
      this.selectableTags = this.$store.state.fileTags.map(x => ({
        ...x,
        text: x.name,
        value: x.idTag
      }))
      this.selectableTags = this.selectableTags.filter(x => !this.hiddenTags.includes(x.idTag));
    },
    remove(tag){
      this.value = this.value.filter(x => x != tag.idTag);
    }
  },
};
</script>