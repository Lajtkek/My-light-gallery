<template>
  <div>
    <Navbar />
    <div>
      <v-card class="edit-image-wrapper">
        <v-tabs v-model="tab" background-color="primary" dark>
          <v-tab key="overview">
            Overview
          </v-tab>
          <v-tab key="b">
            Tags
          </v-tab>
          <v-tab key="c">
            Users
          </v-tab>
        </v-tabs>
        <v-tabs-items v-model="tab" class="edit-wrapper">
          <v-tab-item key="overview">
            <div class="tab-wrapper">
              Overview  
            </div>
          </v-tab-item>
          <v-tab-item key="b">
            <div class="tab-wrapper">
              <TagOverview/>
            </div>
          </v-tab-item>
          <v-tab-item key="c">
            <div class="tab-wrapper">
              Users
            </div>
          </v-tab-item>
        </v-tabs-items>
      </v-card>
    </div>
  </div>
</template>

<script>
import Vue from "vue";
import Navbar from "../components/Navbar.vue";
import TagOverview from "../components/TagOverview.vue";

export default Vue.extend({
  name: "Admin",
  data: function () {
    return {
      tab: "overview"
    };
  },
  components: { Navbar,TagOverview },
  methods: {},
});
</script>

<style lang="less" scoped>
.tab-wrapper{
  min-height: 90vh;
}
</style>
