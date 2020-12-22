<template>
  <div id="app">
    <Counter/>
    <HelloWorld msg="Welcome to Your Vue.js App"/>
  </div>
</template>

<script>
import HelloWorld from './components/HelloWorld.vue'
import Counter from "@/components/Counter";

export default {
  name: 'App',
  components: {
    HelloWorld,
    Counter
  }
}
</script>
