import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
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
    path: '/login',
    name: 'Login',
    meta: {
      requiresGuest: true
    },
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
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
