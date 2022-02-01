<template>
  <div>
    <div v-if="galleryData != null" class="wrapper">
      <div class="graph-wrapper">
        <div class="text">Zaplnění discu</div>
        <v-progress-circular
          :rotate="90"
          :size="300"
          :width="10"
          :value="discFullness"
          color="red"
        >
        <div class="center-text">
          {{discFullness}}%
          <br>
          {{galleryData.totalFileSize}} MB
        </div>
        </v-progress-circular>
      </div>
      <div class="graph-wrapper">
        <div class="text">Zaplnění databáze</div>
        <v-progress-circular
          :rotate="90"
          :size="300"
          :width="10"
          :value="databaseFullness"
          color="green"
        >
        <div class="center-text">
          {{databaseFullness}}%
          <br>
          {{galleryData.totalDatabaseSize}} MB
        </div>
        </v-progress-circular>
      </div>
    </div>
  </div>
</template>

<script>
import Vue from "vue";

export default {
  name: "AdminOverview",
  components: {},
  data: function () {
    return {
      galleryData: null,
    };
  },
  computed: {
    databaseFullness() {
      return parseInt(
        (this.galleryData.totalDatabaseSize /
          this.galleryData.maxDatabaseSize) *
          100
      );
    },
    discFullness() {
      return parseInt(
        (this.galleryData.totalFileSize / this.galleryData.maxFilesize) * 100
      );
    },
  },
  async mounted() {
    let result = await Vue.prototype.get("files/getOverview");
    if (!result.error) {
      this.galleryData = result.data;
    }
  },
  methods: {},
};
</script>

<style lang="less" scoped>
@import "../assets/styles/main.less";
.wrapper {
  .super-flex;
  width: 100%;
  height: 90vh;

  .graph-wrapper{
    margin: 60px;

    .text{
      text-align: center;
      margin: 20px;
      font-size: 24px;
    }
  }
}
</style>
