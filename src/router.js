import Vue from 'vue'
import Router from 'vue-router'
import Tune from './views/Tune.vue'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'tune',
      component: Tune
    },
    {
      path: '/setup',
      name: 'setup',
      // route level code-splitting
      // this generates a separate chunk (about.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import(/* webpackChunkName: "setup" */ './views/Setup.vue')
    }
  ]
})
