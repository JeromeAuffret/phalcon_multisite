import Vue from 'vue';

import index from './Index.vue';
import store from '@/base/store';

Vue.component('login-header', index);
Vue.component('login-footer', index);

const vm = new Vue({
  store,
  el: '#auth_index'
})

vm.$mount();
