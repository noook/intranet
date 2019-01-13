/* eslint-disable */

import store from '@/store';
import axios from 'axios';

const baseURL = process.env.NODE_ENV === 'development' ? 'http://intranet.ml' : 'https://api.intranet.nook.sh';
const api = axios.create({
  baseURL,
});

api.interceptors.request.use(
  (config) => {
    const token = store.state.AUTH_TOKEN || null;
    config.headers.common['X-AUTH-TOKEN'] = token;
    return config;
  },
  (error) => {
    Promise.reject(error);
  });

api.interceptors.response.use(
  (response) => {
    // Do something with response data
    return response;
  },
  (error) => {
    // Do something with response error
    return Promise.reject(error);
  });

export default api;

export function checkConnection() {
  return new Promise((resolve, reject) => api.get('/user/check-connection', {
    headers: {
      'X-AUTH-TOKEN': store.state.AUTH_TOKEN || '',
    },
  })
    .then(response => {
      store.dispatch('setUser', response.data);
      if (response.headers['x-refreshed-token']) {
        store.dispatch('setToken', {
          token: response.headers['x-refreshed-token'],
        });
      }
      resolve();
    })
    .catch((err) => {
      store.dispatch('logout');
      reject();
    }));
};
