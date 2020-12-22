<template>
  <div>
    <h3>Counter</h3>
    <h1>{{ $store.state.count }}</h1>
    <button @click="$store.commit('decrement');">-</button>
    <button @click="$store.commit('increment');">+</button>
  </div>
</template>

<script>
export default {
  name: 'Counter'
};
</script>
