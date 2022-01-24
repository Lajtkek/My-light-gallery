import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import Upload from '../views/Upload.vue'
import store from '../store/index.js'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    meta: {
      //requiresAuth: true
    },
    component: Home
  },
  {
    path: '/upload',
    name: 'Upload',
    meta: {
      requiresAuth: true
    },
    component: Upload
  },
  {
    path: '/login',
    name: 'Login',
    meta: {
      requiresGuest: true
    },
    component: () => import(/* webpackChunkName: "about" */ '../views/Login.vue')
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

router.beforeEach((to, from, next) => {
  const requiresAuth = to.matched.some((x) => x.meta.requiresAuth);
  const requiresGuest = to.matched.some((x) => x.meta.requiresGuest);
  const hasToken = !!localStorage.token;
  
  //TODO check if token is not expired
  if (requiresAuth && !hasToken) {
    next("/login");
  } else if (requiresGuest && hasToken) {
    next("/");
  }  else {
    next();
  }
});

router.afterEach(() => {
  store.commit('setToken', localStorage.token);
})

export default router
