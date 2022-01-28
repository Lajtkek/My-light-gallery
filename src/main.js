import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import vuetify from './plugins/vuetify'

Vue.config.productionTip = false

const MyPlugin = {
  install(Vue) {
    Vue.prototype.parseJwt = (token) => {
      const base64Url = token.split('.')[1];
      const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
      const jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
          return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
      }).join(''));
  
      return JSON.parse(jsonPayload);
    }

    Vue.prototype.get = async (url, data = null, options = {}) => {
      return Vue.prototype.wm(url, data, options, 'GET')
    }

    Vue.prototype.post = async (url, data = null, options = {}) => {
      return Vue.prototype.wm(url, data, options, 'POST')
    }

    Vue.prototype.wm = async (url, data = null, options = {}, method) => {
      let response;
      let success = false;
      let body = null;
      let isFormData = (data instanceof FormData);

      if(method != "GET"){
        if(isFormData)
          body = data;
        else
          body = JSON.stringify(data);
      }

      let urlParam = "";
      if(method == "GET" && data){
        urlParam = data;
      }

      try{
        response = await fetch(`${process.env.VUE_APP_API_URL}${url}${process.env.VUE_APP_API_END}${urlParam}`, {
          method,
          mode: 'cors',
          headers: {
            'Content-Type':  isFormData ? 'application/x-www-form-urlencoded' : 'application/json',
            'Authorization' : `Bearer ${localStorage.token}`
          },
          body
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
        success: success && !responseJson.error,
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
