import Vue from 'vue';
import Router from 'vue-router';
import store from './store';
import { checkConnection } from './utils/api';

import Home from './views/Home.vue';
import Authentication from './views/Authentication.vue';
import AdminPanel from './views/Admin/Admin.vue';
import AdminUserList from './views/Admin/Users/List.vue';
import AdminCoursesList from './views/Admin/Courses/List.vue';
import AdminTeachersList from './views/Admin/Teachers/List.vue';
import AdminStudentDetail from './views/Admin/Students/Detail.vue';
import AdminStudentsList from './views/Admin/Students/List.vue';
import AdminCoursesNew from './views/Admin/Courses/New.vue';
import AdminCourseDetail from './views/Admin/Courses/Detail.vue';

import CoursesList from './views/Courses/List.vue';

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
      path: '/courses',
      name: 'courses',
      component: CoursesList,
      beforeEnter(to, from, next) {
        if (store.getters.isAdmin) {
          next('/admin/courses');
        }
        next();
      },
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
          name: 'courses-list-admin',
          component: AdminCoursesList,
        },
        {
          path: 'courses/:id',
          name: 'course-detail',
          component: AdminCourseDetail,
        },
        {
          path: 'courses/new',
          name: 'new-course',
          component: AdminCoursesNew,
        },
        {
          path: 'teachers',
          name: 'teachers-list',
          component: AdminTeachersList,
        },
        {
          path: 'students',
          name: 'students-list',
          component: AdminStudentsList,
        },
        {
          path: 'students/:id',
          name: 'student-detail',
          component: AdminStudentDetail,
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
