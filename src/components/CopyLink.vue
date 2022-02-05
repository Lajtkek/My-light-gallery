<template>
  <v-icon class="copy-permalink" @click="copy(link)">{{icon}}</v-icon>
</template>

<script>
import { copyToClipboard } from "../assets/js/common";

export default {
  name: "CopyLink",
  props: ["link"],
  data: function () {
    return {
      icon: "mdi-content-copy"
    };
  },
  methods: {
    copy(link){
      if(this.icon == "mdi-check-outline")
        return;

      this.icon = "mdi-check-outline";

      setTimeout(() => {
        this.icon = "mdi-content-copy";
      }, 3000)
      
      copyToClipboard(link);
    }
  },
};
</script>

<style lang="less" scoped>
</style>
