<template>
  <div>
    <h1 class="project-title">My Light Gallery</h1>
    <h5 class="project-info">Small open-source project for organizing images and videos.</h5>
    <h5 class="project-info">v {{version}}</h5>
    <router-link to="/overview" class="explore-gallery-btn">Explore light gallery</router-link>
    <router-link v-if="!$store.state.token" to="/login" class="login-btn">Do you have account? Login</router-link>
    <div class="info-wrapper">
      <div v-for="(i, index) in info" :key="index">
        <h2>{{i.title}}</h2>
        <h4>{{i.text}}</h4>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
  import Vue from 'vue'

  export default Vue.extend({
    name: 'Homepage',
    components: {
    },
    data: function(){
      return {
        version: process.env.VUE_APP_VERSION,
        info: [{
          title: "Light",
          text: "Backend API is made with MySQL and PHP. Frontend is made in VUE."
        },{
          title: "Easy to organize",
          text: "Add tag to uploaded file so you can easily find it later."
        },{
          title: "Secure",
          text: "I hope."
        },{
          title: "Open source",
          text: "All code can be found on github."
        }]
      }
    },
    methods: {
    },
    async mounted(){
    }
  })
</script>

<style lang="less" scoped>
  @import '../assets/styles/main.less';
  .project-title{
    margin-top: 10vh;
    text-align: center;
  }

  .project-info{
    text-align: center;
  }

  .explore-gallery-btn{
    padding: 15px;
    background: #6776ff;
    margin: auto;
    text-align: center;
    margin: 20px auto;
    display: block;
    width: fit-content;
    color: black;
    text-decoration: none;
  }

  .login-btn{
    padding: 15px;
    margin: auto;
    text-align: center;
    margin: 20px auto;
    display: block;
    width: fit-content;
  }

  .info-wrapper{
    width: 800px;
    margin: auto;

    >div{
      margin: 20px;
      width: 100%;
    }

    >div:nth-child(even){
      float: left;
      text-align: left;
    }

    >div:nth-child(odd){
      float: right;
      text-align: right;
    }
  }
</style>