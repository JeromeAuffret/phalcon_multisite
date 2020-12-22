<template>
  <div>
    <Counter/>
    <HelloWorld msg="Welcome !"/>
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
