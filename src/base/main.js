import Vue from 'vue';
import App from '@/base/App.vue';
import store from '@/base/store';

Vue.config.productionTip = false;

new Vue({
  store,
  render: h => h(App),
}).$mount('#hello_world');
