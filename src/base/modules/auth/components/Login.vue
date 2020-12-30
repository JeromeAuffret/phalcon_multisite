<template>
  <form method="post">
    <input type="hidden" name="<?php echo $this->security->getTokenKey() ?>" value="<?php echo $this->security->getToken() ?>"/>

    <div class="form-group">
      <label for="login">Email</label>
      <input id="login" type="text" name="login" class="form-control" required />
    </div>

    <div class="form-group">
      <label for="password">Password</label>
      <input id="password" type="password" name="password" class="form-control" required />
    </div>

    <div class="row btn-container">
      <button type="submit" class="btn btn-primary">Sign In</button>
      <button type="button" class="btn btn-secondary">Forgot Password</button>
    </div>
  </form>
</template>

<script>
export default {
  name: 'Login'
}
</script>
