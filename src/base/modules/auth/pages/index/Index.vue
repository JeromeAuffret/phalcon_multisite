<template>
  <div>
    <Counter/>
    <HelloWorld msg="Welcome !"/>
  </div>
</template>

<script>
import Counter from "@/base/components/Counter";
import HelloWorld from "@/base/modules/auth/components/HelloWorld";

export default {
  name: 'App',
  components: {
    HelloWorld,
    Counter
  }
}
</script>
