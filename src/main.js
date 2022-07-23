import Vue from 'vue';
import App from './App.vue';
import router from './router/router';
import store from './store/store';
import vuetify from './plugins/vuetify';
import { isJson } from '../src/assets/js/common';

import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';

Vue.config.productionTip = false;

const options = {
    position: 'top-center',
    timeout: 5000,
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    closeButton: 'button',
    icon: true,
    rtl: false,
};

Vue.use(Toast, options);

const MyPlugin = {
    install(Vue) {
        Vue.prototype.showMainTooltip = (msg) => {
            Vue.prototype.$toast(msg);
        };

        Vue.prototype.showSuccessTooltip = (msg) => {
            Vue.prototype.$toast.success(msg);
        };

        Vue.prototype.showErrorTooltip = (msg) => {
            Vue.prototype.$toast.error(msg);
        };

        Vue.prototype.parseJwt = (token) => {
            const base64Url = token.split('.')[1];
            const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
            const jsonPayload = decodeURIComponent(
                atob(base64)
                    .split('')
                    .map(function (c) {
                        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
                    })
                    .join('')
            );

            return JSON.parse(jsonPayload);
        };

        Vue.prototype.hasRole = (roleName) => {
            return store.state.userData?.roles?.includes(roleName);
        };

        Vue.prototype.hasRoles = (roleNames, userData = store.state.userData) => {
            for (const roleName of roleNames) {
                if (!userData?.roles?.includes(roleName)) return false;
            }
            return true;
        };

        Vue.prototype.getFile = (fileName) => {
            return `${process.env.VUE_APP_IMAGE_ROOT}/${fileName}`;
        };

        Vue.prototype.patch = async (url, data = null, options = {}) => {
            return Vue.prototype.wm(url, data, options, 'PATCH');
        };

        Vue.prototype.put = async (url, data = null, options = {}) => {
            return Vue.prototype.wm(url, data, options, 'PUT');
        };

        Vue.prototype.get = async (url, data = null, options = {}) => {
            return Vue.prototype.wm(url, data, options, 'GET');
        };

        Vue.prototype.post = async (url, data = null, options = {}) => {
            return Vue.prototype.wm(url, data, options, 'POST');
        };

        // options is waiting for implementation
        // eslint-disable-next-line no-unused-vars
        Vue.prototype.wm = async (url, data = null, options = {}, method) => {
            return new Promise((resolve) => {
                let body = null;
                let isFormData = data instanceof FormData;

                if (method != 'GET') {
                    if (isFormData) body = data;
                    else body = JSON.stringify(data);
                }

                let urlParam = '';
                if (method == 'GET' && data) {
                    urlParam = '?';
                    for (const [key, value] of Object.entries(data)) {
                        if (value && (!Array.isArray(value) || value.length > 0)) urlParam += `&${key}=${value}`;
                    }
                    urlParam = urlParam.replace('&', '');
                }

                try {
                    var xhr = new XMLHttpRequest();
                    xhr.open(method, `${process.env.VUE_APP_API_URL}${url}${process.env.VUE_APP_API_END}${urlParam}`);

                    xhr.setRequestHeader('Accept', 'application/json');
                    xhr.setRequestHeader('Authorization', `Bearer ${localStorage.token}`);
                    xhr.setRequestHeader('Content-Type', 'application/json');

                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4) {
                            if (isJson(xhr.responseText)) {
                                let data = JSON.parse(xhr.responseText);
                                if (!data.error)
                                    resolve({
                                        data,
                                    });
                                else
                                    resolve({
                                        error: data.error,
                                    });
                            } else {
                                resolve({
                                    data: xhr.responseText,
                                });
                            }
                        }
                    };

                    xhr.onerror = function (e) {
                        console.warn('Error occured when resolving xhr request');
                        console.warn(e);
                        resolve({
                            error: e,
                        });
                    };

                    xhr.send(body);
                } catch (e) {
                    console.warn('Error occured in XHR:');
                    console.warn(e);
                    resolve({
                        error: e,
                    });
                }
            });
        };
    },
};

Vue.use(MyPlugin);

new Vue({
    router,
    store,
    vuetify,
    render: (h) => h(App),
}).$mount('#app');
