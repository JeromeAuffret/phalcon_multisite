import Vue from 'vue';

import index from './Index.vue';
import store from '@/base/store';
import Login from "@/base/modules/auth/components/Login";

const vm = new Vue({
  store,
  el: '#auth_index',
  components: {
    'welcome': index,
    Login
  }
})

vm.$mount();
