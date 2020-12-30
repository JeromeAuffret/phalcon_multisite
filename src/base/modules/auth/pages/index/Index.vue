<template>
  <div>
    <div class="header">
      <div class="media">
        <!--					<img src="img/logo-large.png" alt="Login Logo" class="mx-auto" style="max-width: 150px;">-->
      </div>
    </div>

    <counter></counter>
    <hello-world msg="Welcome !"></hello-world>

  </div>
</template>

<script>
import Counter from "@/base/components/Counter";
import HelloWorld from "@/base/modules/auth/components/HelloWorld";

import './index.css';

export default {
  name: 'Index',
  components: {
    HelloWorld,
    Counter
  }
}
</script>
