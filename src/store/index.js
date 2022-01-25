import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    token: null,
    userData: null,
    fileTags: [],
  },
  mutations: {
    async getFileTags(state){
      let result = await Vue.prototype.get("tags/getAllTags");
      if(result.success){
        state.fileTags = result.data;
      }
      console.log(state.fileTags);
    },
    setToken(state, token){
      if(token){
        try{
          let result = Vue.prototype.parseJwt(token)
          state.token = token;
          state.userData = result;
        }catch(e){
          console.error(e);
          state.token = null;
          state.userData = null;
          localStorage.removeItem("token");
        }
      }else{
        state.token = null;
        state.userData = null;
        localStorage.removeItem("token");
      }
    }
  },
  actions: {
  },
  modules: {
  }
})
