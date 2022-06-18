<template>
  <div>
    <v-data-table :headers="headers" :items="codes" :items-per-page="50">
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>RegisterCodes</v-toolbar-title>
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-spacer></v-spacer>
        </v-toolbar>
      </template>
    </v-data-table>
  </div>
</template>

<script>
import Vue from "vue";

export default {
  name: "RegisterCodesOverview",
  data: function () {
    return {
        headers: [
            { text: "ID", value: "idRegisterCode" },
            { text: "Code", value: "code" },
            { text: "Used", value: "used" },
        ],
        codes: [],
    };
  },
  async mounted() {
    let result = await Vue.prototype.get("registerCodes/getRegisterCodes", {});
    if (!result.error) {
        this.codes = result.data;
    }
  },
  methods: {
    onUserIU(user) {
      let index = this.users.findIndex((x) => x.idUser == user.idUser);
      if (index === -1) index = this.users.length;
      this.users = this.users.filter((x) => x.idUser != user.idUser);
      this.users.splice(index, 0, user);
    },
    createUser() {
      this.$store.commit("setEditedUser", {
        username: "",
        password: "",
        email: "",
        roles: [],
        isApproved: true,
      });
    },
    editUser(user) {
      user = { ...user };
      user.roles = user.roles?.map((x) => x.idRole) ?? [];
      this.$store.commit("setEditedUser", user);
    },
    // deleteUser(){

    // }
  },
};
</script>

<style lang="less" scoped>
</style>
