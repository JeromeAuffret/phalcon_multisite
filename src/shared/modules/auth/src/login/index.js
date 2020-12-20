import Vue from 'vue';
import App from './index/App.vue';
import store from './index/store';

Vue.config.productionTip = false;

new Vue({
  store,
  render: h => h(App),
}).$mount('#app');
