<template>
  <div class="welcome-alert">
    <div class="alert alert-warning" role="alert">
      <h4 class="alert-heading">{{msg}}</h4>
      <div>you can try the following credentials</div>
      <ul>
        <li>Admin role : Login <strong>admin</strong> and password <strong>admin</strong></li>
        <li>Demo 1 : Login <strong>demo1</strong> and password <strong>demo1</strong></li>
        <li>Demo 2 : Login <strong>demo2</strong> and password <strong>demo2</strong></li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  name: 'HelloWorld',
  props: {
    msg: String
  }
}
</script>

<style scoped>
.welcome-alert {
  margin: 15px 0;
}
</style>
