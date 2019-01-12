import Vue from 'vue';
import Router from 'vue-router';

import Home from './views/Home.vue';
import Authentication from './views/Authentication.vue';

Vue.use(Router);

export default new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home,
    },
    {
      path: '/(login|register)',
      name: 'authentication',
      component: Authentication,
    },
  ],
});
