<template>
  <div>
    <HelloWorld msg="Welcome !"></HelloWorld>
  </div>
</template>

<script>
import './index.css';
import HelloWorld from "@/base/modules/auth/components/HelloWorld";

export default {
  name: 'Index',
  components: {
    HelloWorld
  }
}
</script>
