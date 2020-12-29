import Vue from 'vue';
import App from './Index.vue';
import store from '@/base/store';

Vue.config.productionTip = false;

new Vue({
  store,
  render: h => h(App),
}).$mount('#auth_index');
