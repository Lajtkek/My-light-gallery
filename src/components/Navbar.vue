<template>
  <div class="nav">
    <div class="left">
      <router-link
      to="/"
      >Home</router-link>
      <router-link
      to="/upload"
      v-if="$store.state.token"
      >Upload</router-link>
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
  import store from '../store/index.js'

  export default {
    name: 'Navbar',
    mounted(){
      console.log(store.state.token)
    },
    methods: {
      logout(){
        store.commit("setToken", null);
      }
    },
  }
</script>

<style lang="less" scoped>
  .nav{
    padding: 10px;

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
