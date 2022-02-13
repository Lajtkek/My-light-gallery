<template>
  <v-dialog v-model="showDialog" max-width="500px">
    <v-card v-if="user != null">
      <v-container>
        <v-row>
          <v-col cols="6" sm="12" md="6">
            <v-text-field
              v-model="user.username"
              label="Username"
            ></v-text-field>
          </v-col>
          <v-col cols="6" sm="12" md="6">
            <v-text-field
              v-model="user.password"
              type="password"
              label="Password"
            ></v-text-field>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="6" sm="12" md="6">
            <v-text-field
              v-model="user.email"
              label="Email"
            ></v-text-field>
          </v-col>
          <v-col cols="6" sm="12" md="6">
            <v-checkbox
              v-model="user.isApproved"
              label="Approved"
            ></v-checkbox>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" sm="12" md="12">
            <v-autocomplete
              v-model="user.roles"
              :items="roles"
              outlined
              dense
              chips
              small-chips
              label="Outlined"
              multiple
            ></v-autocomplete>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" sm="12" md="12">
            <v-btn
              :loading="pendingRequests.saveUser"
              @click="saveUser"
              class="float-right"
              :disabled="user.username.length < 3 || user.username.length < 3"
              >Save</v-btn
            >
          </v-col>
        </v-row>
      </v-container>
    </v-card>
  </v-dialog>
</template>

<script>
import Vue from "vue";
import store from "../store/store.js";

export default {
  name: "UserEditor",
  data: function () {
    return {
      showDialog: false,
      user: null,
      pendingRequests: {
        saveUser: false,
      },
    };
  },
  computed: {
    roles(){
      return this.$store.state.userRoles.map(x => ({
        text: x.name,
        value: x.idRole
      }))
    }
  },
  watch: {
    "$store.state.editedUser": function () {
      this.showDialog = store.state.editedUser != null;
      this.user = store.state.editedUser;
    },
  },
  mounted(){
    this.$store.commit("getUserRoles");
  },
  methods: {
    async saveUser() {
      this.pendingRequests.saveUser = true;
      let { idUser, username, password, email, isApproved, roles } = { ...this.user };

      let result = null;
      if (this.user.idUser) {
        result = await Vue.prototype.patch("users/userIU", {
          idUser,
          username, 
          password,
          email,
          isApproved, 
          roles
        });
      } else {
        result = await Vue.prototype.post("users/userIU", {
          username, 
          password,
          email,
          isApproved, 
          roles
        });
      }
      if (!result.error) {
        this.$emit('onUserIU', result.data)
        this.$store.commit("setEditedUser", null);
      } else {
        this.showErrorTooltip(result.error);
      }
      this.pendingRequests.saveUser = false;
    },
  },
};
</script>

<style lang="less" scoped>
</style>
