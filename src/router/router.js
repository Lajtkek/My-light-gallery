import Vue from 'vue'
import VueRouter from 'vue-router'
import Homepage from '../views/Homepage.vue'
import Gallery from '../views/Gallery.vue'
import Admin from '../views/Admin.vue'
import Upload from '../views/Upload.vue'
import Detail from '../views/Detail.vue'
import store from '../store/store.js'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Homepage',
    meta: {
    },
    component: Homepage
  },
  {
    path: '/overview',
    name: 'Gallery',
    meta: {
    },
    component: Gallery
  },
  {
    path: '/detail/:id',
    name: 'Detail',
    meta: {
    },
    component: Detail
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
    path: '/admin',
    name: 'Admin',
    meta: {
      requiresAuth: true,
      requiredRoles: ['admin']
    },
    component: Admin
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
  const requiredRoles = to.meta.requiredRoles ?? [];
  const hasToken = !!localStorage.token;

  let userData = {};
  if(hasToken)
    userData = Vue.prototype.parseJwt(localStorage.token)
  
  //TODO check if token is not expired
  if (requiresAuth && !hasToken) {
    next("/login");
  } else if (requiresGuest && hasToken) {
    next("/");
  }  else {
    if(Vue.prototype.hasRoles(requiredRoles, userData)){
      next();
    }else{
      next(false);
    }
  }
});

router.afterEach(() => {
  store.commit('setToken', localStorage.token);
})

export default router
