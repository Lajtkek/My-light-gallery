<template>
  <div>
    <Navbar />
    <div>
      <v-card class="edit-image-wrapper">
        <v-tabs v-model="tab" background-color="primary" dark>
          <v-tab v-for="i in 3" :key="i">
            {{i}}
          </v-tab>
        </v-tabs>
        <v-tabs-items v-model="tab" class="edit-wrapper">
          <v-tab-item :key="0">
            a
          </v-tab-item>
          <v-tab-item :key="1">
            b
          </v-tab-item>
          <v-tab-item :key="2">
            c
          </v-tab-item>
        </v-tabs-items>
      </v-card>
    </div>
  </div>
</template>

<script>
import Vue from "vue";
import Navbar from "../components/Navbar.vue";

export default Vue.extend({
  name: "Admin",
  data: function () {
    return {
      tab: 0
    };
  },
  components: { Navbar },
  methods: {},
});
</script>

<style lang="less" scoped>
</style>
