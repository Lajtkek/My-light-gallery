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
    }
  },
  data: function(){
    return {
      serachInput: "",
      value: [],
      selectableTags: [],
    }
  },
  watch: {
    '$store.state.fileTags': function(){
      this.refreshTags();
    },
    'value': function(val){
      this.$emit('input', val);
    }
  },
  mounted() {
    this.refreshTags();
  },
  methods: { 
    refreshTags(){
      this.selectableTags = this.$store.state.fileTags.map(x => ({
        ...x,
        text: x.name,
        value: x.idTag
      }))
    },
    remove(tag){
      this.value = this.value.filter(x => x != tag.idTag);
    }
  },
};
</script>
