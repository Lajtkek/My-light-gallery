import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import vuetify from './plugins/vuetify'

Vue.config.productionTip = false

const MyPlugin = {
  install(Vue:any) {
    Vue.prototype.parseJwt = (token:string) => {
      const base64Url = token.split('.')[1];
      const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
      const jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
          return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
      }).join(''));
  
      return JSON.parse(jsonPayload);
    }

    Vue.prototype.saveToken = (token: string) => {
      let parsedToken = null;
      try{
        parsedToken = Vue.prototype.parseJwt(token)
      }catch(e){
        console.log(`Cant parse token:${token}`)
      }

      //TODO: find better way to store user data
      (<any>window).UserData = parsedToken;

      localStorage.token = token;
    }
    
    Vue.prototype.post = async (url:string, data = {}, options:any = {}) => {
      return Vue.prototype.wm(url, data, options = {}, 'POST')
    }

    Vue.prototype.wm = async (url:string, data = {}, options:any = {}, method:string) => {
      let response;
      let success = false;

      try{
        response = await fetch(`${process.env.VUE_APP_API_URL}/${url}${process.env.VUE_APP_API_END}`, {
          method: method,
          headers: {
            'Content-Type': 'application/json',
            'Authorization' : `Bearer ${localStorage.token}`
          },
          body: JSON.stringify(data),
        })
      }catch(e){
        if(options.onFail)
          await options.onFail()
        else  
          console.log("fail")

        if(options.allways)
          await options.allways()

        return {
          success,
          error: e
        }
      }

      let responseJson = {};
      await response.json()
      .then(data => {
        responseJson = data;
        success = true;
      })
      .catch((e) => { console.log(e); })

      return {
        success,
        data: responseJson
      };
    }
  }
}
Vue.use(MyPlugin)

new Vue({
  router,
  store,
  vuetify,
  render: h => h(App)
}).$mount('#app')
