import Vue from 'vue';
import VueResource from 'vue-resource';

// import index from './Index.vue';
// import store from '@/base/store';

// new Vue({
//   store,
//   render: h => h(App),
// }).$mount('#auth_index');

Vue.use(VueResource)
Vue.component('login-header', {
  data: function () {
    return {
      count: 0
    }
  },
  template: '<button v-on:click="count++">Vous m\'avez cliqué {{ count }} fois.</button>'
})

const vm = new Vue({
  el: '#toto',
  // render: h => h(App),
});

window.vm = vm
