import Vue from 'vue';
import Router from 'vue-router';
import store from './store';
import { checkConnection } from './utils/api';

import Home from './views/Home.vue';
import Authentication from './views/Authentication.vue';

import StudentGrades from './views/Student/Grades.vue';

import AdminPanel from './views/Admin/Panel.vue';
import TeacherPanel from './views/Teacher/Panel.vue';
import AdminUserList from './views/Admin/Users/List.vue';

import AdminTeachersList from './views/Admin/Teachers/List.vue';
import AdminTeachersDetail from './views/Admin/Teachers/Detail.vue';

import AdminStudentDetail from './views/Admin/Students/Detail.vue';
import AdminStudentsList from './views/Admin/Students/List.vue';

import AdminCoursesList from './views/Admin/Courses/List.vue';
import AdminCoursesNew from './views/Admin/Courses/New.vue';
import AdminCourseDetail from './views/Admin/Courses/Detail.vue';
import AdminCourseManage from './views/Admin/Courses/ManageStudents.vue';
import AdminGradeEdit from './views/Admin/Grades/Edit.vue';
import TeacherCourseDetail from './views/Teacher/CourseDetail.vue';

import TeacherGradeAction from './views/Teacher/GradeAction.vue';

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
      path: '/grades',
      name: 'student-grades-list',
      component: StudentGrades,
    },
    {
      path: '/teacher',
      name: 'teacher-panel',
      component: TeacherPanel,
    },
    {
      path: '/teacher/courses/:id',
      name: 'teacher-course-detail',
      component: TeacherCourseDetail,
    },
    {
      path: '/teacher/courses/:id/grade',
      name: 'teacher-grade-action',
      component: TeacherGradeAction,
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
          path: 'courses/new',
          name: 'new-course',
          component: AdminCoursesNew,
        },
        {
          path: 'courses/:id',
          name: 'course-detail',
          component: AdminCourseDetail,
        },
        {
          path: 'teachers',
          name: 'teachers-list',
          component: AdminTeachersList,
        },
        {
          path: 'teachers/:id',
          name: 'teacher-detail',
          component: AdminTeachersDetail,
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
    {
      name: 'course-student-manage',
      path: '/admin/course/:id/manage',
      component: AdminCourseManage,
    },
    {
      name: 'admin-grade-edit',
      path: '/admin/students/:student/grades/:grade/edit',
      component: AdminGradeEdit,
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
