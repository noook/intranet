import Vue from 'vue';
import Router from 'vue-router';
import { checkConnection } from './utils/api';

import Home from './views/Home.vue';
import Authentication from './views/Authentication.vue';
import AdminPanel from './views/Admin/Admin.vue';
import AdminUserList from './views/Admin/Users/List.vue';
import AdminCoursesList from './views/Admin/Courses/List.vue';

Vue.use(Router);

const router = new Router({
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
    {
      path: '/admin',
      name: 'admin-panel',
      component: AdminPanel,
      children: [
        {
          path: 'users',
          name: 'users-list',
          component: AdminUserList,
        },
        {
          path: 'courses',
          name: 'courses-list',
          component: AdminCoursesList,
        },
      ],
    },
  ],
});

router.beforeEach((to, from, next) => {
  if (to.name !== 'authentication') {
    checkConnection()
      .then(() => next())
      .catch(() => next('/login'));
  }
  next();
});

export default router;
