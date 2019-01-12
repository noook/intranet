import Vue from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';

import '@/utils/mixins/translations';
import api from './utils/api';

Vue.config.productionTip = false;
Vue.prototype.$api = api;
Vue.prototype.$lang = 'en';

new Vue({
  router,
  store,
  render: h => h(App),
}).$mount('#app');
