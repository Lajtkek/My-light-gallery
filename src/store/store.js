import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    token: null,
    userData: null,
    fileTags: [],
    editedTag: null,
    editedUser: null
  },
  mutations: {
    async setEditedTag(state, tag){
      state.editedTag = tag;
    },
    async getFileTags(state){
      let result = await Vue.prototype.get("tags/getAllTags");
      if(!result.error){
        state.fileTags = result.data;
      }
    },
    async fileTagIU(state, tag){
      state.fileTags = state.fileTags.filter(x => x.idTag != tag.idTag);
      state.fileTags.push(tag);
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
