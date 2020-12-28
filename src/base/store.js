import Vue from 'vue';
import Vuex from 'vuex';
import sharedMutations from 'vuex-shared-mutations';

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    count: 0,
  },
  mutations: {
    increment(state) {
      state.count += 1;
    },
    decrement(state) {
      state.count -= 1;
    }
  },
  plugins: [
      sharedMutations({
        predicate: ['increment', 'decrement']
      })
  ],
});
