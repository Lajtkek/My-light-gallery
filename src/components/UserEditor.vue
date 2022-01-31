<template>
  <v-dialog v-model="showDialog" max-width="500px">
    <v-card v-if="user != null">
      <v-container>
        <v-row>
          <v-col cols="6" sm="6" md="6">
            <v-text-field
              v-model="user.username"
              label="Tag name"
            ></v-text-field>
            <v-checkbox
              v-model="user.isApproved"
              label="Public"
            ></v-checkbox>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" sm="12" md="12">
            <v-btn :loading="pendingRequests.saveUser" @click="saveUser" class="float-right" :disabled="user.username.length < 3 || user.username.length < 3">Save</v-btn>
          </v-col>
        </v-row>
      </v-container>
    </v-card>
  </v-dialog>
</template>

<script>
import Vue from "vue";
import store from '../store/store.js'

export default {
  name: "UserEditor",
  props: {
  },
  data: function () {
    return {
      showDialog: false,
      user: null,
      pendingRequests: {
        saveUser: false,
      }
    };
  },
  watch: {
    '$store.state.editedUser': function(){
      this.showDialog = store.state.editedUser != null;
      this.user = store.state.editedUser;
    }
  },
  methods: {
    async saveUser(){
      this.pendingRequests.saveUser = true;
      if(this.user.idUser){
        console.warn("not implemented")
      }else{
        let {name, code, color, isPublic} = {...this.user};
        let result = await Vue.prototype.post("tags/userIU", {name, code, color, isPublic});

        if(!result.error){
          this.$store.commit('userIU', result.data);
          this.$store.commit('setEditedUser', null);
        }else{
          console.warn(`Error occured when saving user error code: ${result.error}`);
        }
        this.pendingRequests.saveUser = false;
      }
    }
  },
};
</script>

<style lang="less" scoped>
</style>
