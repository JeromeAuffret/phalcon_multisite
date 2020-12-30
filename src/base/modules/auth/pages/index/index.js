import Vue from 'vue';

import index from './Index.vue';
import Login from "@/base/modules/auth/components/Login";

const vm = new Vue({
  el: '#auth_index',
  components: {
    'welcome': index,
    Login
  }
})

vm.$mount();
