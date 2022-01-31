<template>
  <div>
    <v-data-table
      :headers="headers"
      :items="users"
      :items-per-page="50"
    >
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>Users</v-toolbar-title>
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-spacer></v-spacer>
          <v-btn @click="createUser()">Create new user</v-btn>
        </v-toolbar>
      </template>
      <template v-slot:item.roles="{ item }">
        <v-chip v-for="role in item.roles" :key="role.idRole">
          {{ role.name }}
        </v-chip>
      </template>
    </v-data-table>
    <UserEditor/>
  </div>
</template>

<script>
import Vue from "vue";
import UserEditor from "../components/UserEditor.vue";

export default {
  name: "UserOverview",
  components: { UserEditor },
  data: function () {
    return {
      headers: [
        { text: "ID", value: "idUser" },
        { text: "Username", value: "username" },
        { text: "Email", value: "email" },
        { text: "isApproved", value: "isApproved" },
        { text: "createdAt", value: "createdAt" },
        { text: "Roles", value: "roles" },
      ],
      users: [],
    };
  },
  async mounted() {
    let result = await Vue.prototype.get("users/getUsers", {});
    if(!result.error){
      this.users = result.data;
    }
  },
  methods: {
    createUser() {
      this.$store.commit("setEditedUser", {
        username: "",
        password: "",
        email: "",
        roles: [],
        isApproved: true,
      });
    },
  },
};
</script>

<style lang="less" scoped>
</style>
