import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    token: null,
    userData: null,
    fileTags: [],
    userRoles: [],
    editedTag: null,
    editedUser: null
  },
  mutations: {
    async getUserRoles(state){
      let result = await Vue.prototype.get("roles/getRoles");
      if(!result.error){
        state.userRoles = result.data;
      }
    },
    async setEditedTag(state, tag){
      state.editedTag = tag;
    },
    async getFileTags(state, detail){
      let result = await Vue.prototype.get("tags/getTags", {
        detail
      });
      if(!result.error){
        state.fileTags = result.data;
      }
    },
    async fileTagIU(state, tag){
      let index = state.fileTags.findIndex(x => x.idTag == tag.idTag)
      state.fileTags = state.fileTags.filter(x => x.idTag != tag.idTag);
      state.fileTags.splice(index, 0, tag);
      this.onTagIU?.(tag);
    },
    async setEditedUser(state, user){
      state.editedUser = user;
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
