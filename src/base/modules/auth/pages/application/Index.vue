<template>
  <div>
    <Counter/>
    <HelloWorld msg="Welcome !"/>
    <Coffee />
  </div>
</template>

<script>
import Counter from "@/base/components/Counter";
import HelloWorld from "@/base/modules/auth/components/HelloWorld";
import Coffee from "@/base/components/Coffee";

export default {
  name: 'App',
  components: {
    HelloWorld,
    Counter,
    Coffee
  }
}
</script>
