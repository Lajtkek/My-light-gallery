import Vue from 'vue'
import VueRouter, { RouteConfig } from 'vue-router'
import Home from '../views/Home.vue'

Vue.use(VueRouter)

const routes: Array<RouteConfig> = [
  {
    path: '/',
    name: 'Home',
    meta: {
      requiresAuth: true
    },
    component: Home
  } as any,
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
  const hasToken = !!localStorage.getItem("token");

  //TODO check if token is not expired
  console.log({
    requiresAuth,
    requiresGuest,
    hasToken
  })
  if (requiresAuth && !hasToken) {
    next("/login");
  } else if (requiresGuest && hasToken) {
    next("/");
  }  else {
    next();
  }
});

export default router
