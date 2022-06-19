<template>
  <div class="nav">
    <div class="left">
      <router-link
      to="/overview"
      >Overview</router-link>
      <router-link
      to="/upload"
      >Upload</router-link>
      <router-link
      to="/admin"
      v-if="hasRole('admin')"
      >Admin</router-link>
    </div>
    <div class="right">
      <div v-if="$store.state.token">
      Welcome {{$store.state.userData.username}}!&nbsp;
      <v-btn color="blue" @click="logout" right>
        Logout
      </v-btn>
    </div>
    <router-link
      v-else
      to="/login"
      custom
    >Login</router-link>
    </div>
  </div>
</template>

<script>
  import store from '../store/store.js'

  export default {
    name: 'Navbar',
    mounted(){
      //console.log(store.state.token)
    },
    methods: {
      logout(){
        store.commit("setToken", null);
        location.reload();
      }
    },
  }
</script>

<style lang="less" scoped>
  .nav{
    padding: 10px;
    height: 50px;
    .left{
      float:left;
      a{
        margin-right: 10px;
        margin-left: 10px;
      } 
    }

    .right{
      float: right;
    }
  }
</style>
