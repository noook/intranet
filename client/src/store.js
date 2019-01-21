/* eslint-disable no-param-reassign */

import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';

export default new Vuex.Store({
  state: {
    AUTH_TOKEN: localStorage.getItem('AUTH_TOKEN') || null,
    USER: localStorage.getItem('USER') || null,
    ROLES: localStorage.getItem('ROLES') || [],
  },
  getters: {
    isAdmin(state) {
      return state.ROLES.includes('ROLE_ADMIN');
    },
    isTeacher(state) {
      return state.ROLES.includes('ROLE_TEACHER');
    },
    isStudent(state) {
      return state.ROLES.includes('ROLE_STUDENT');
    },
  },
  mutations: {
    SET_TOKEN(state, data) {
      state.AUTH_TOKEN = data.token;
      localStorage.setItem('AUTH_TOKEN', data.token);
    },
    SET_USER(state, data) {
      localStorage.setItem('USER', data.user.email);
      localStorage.setItem('ROLES', data.user.roles);
      Vue.set(state, 'USER', data.user.email);
      Vue.set(state, 'ROLES', data.user.roles);
    },
    LOGOUT(state) {
      Vue.delete(state, 'AUTH_TOKEN');
      Vue.delete(state, 'USER');
      Vue.delete(state, 'ROLES');
      localStorage.removeItem('AUTH_TOKEN');
      localStorage.removeItem('USER');
      localStorage.removeItem('ROLES');
    },
  },
  actions: {
    setToken(context, data) {
      context.commit('SET_TOKEN', data);
    },
    setUser(context, data) {
      context.commit('SET_USER', data);
    },
    logout(context) {
      context.commit('LOGOUT');
    },
  },
  strict: debug,
});
