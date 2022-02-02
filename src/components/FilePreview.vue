<template>
  <div class="wrapper">
    <img v-if="fileType == 'image'" :src="file.base64 ? file.base64 : getFile(file.permalink)" />
    <video controls  v-else-if="fileType == 'video'">
      <source :src="file.base64 ? file.base64 : getFile(file.permalink)" :type="file.filyType" />
      Your browser does not support the video tag.
    </video>
    <div v-else>Unsuported</div>
  </div>
</template>

<script>
export default {
  name: 'FilePreview',
  components: {},
  data() {
    return {};
  },
  props: {
    file: {
      type: Object,
      required: true,
    },
  },
  computed: {
    fileType() {
      let mimeType = this.file.mimeType;
      return mimeType.split("/")[0];
    },
  },
  mounted() {},
  methods: {},
};
</script>

<style lang="less" scoped>
@import "../assets/styles/main.less";
.wrapper {
  .super-flex; 
  
  width: 100%;
  height: 100%;
}

img, video { 
  max-width: calc(100% - 20px);
  max-height: calc(100% - 20px);
}
</style>
