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
      <button v-on:click="sayWelcome" type="submit" class="btn btn-primary">Sign In</button>
      <button v-on:click="forgotPassword" type="button" class="btn btn-secondary">Forgot Password</button>
    </div>
  </form>
</template>

<script>
import $ from 'jquery';

export default {
  name: 'Login',
  mounted: function () {
    $('.alert-warning').addClass('alert-danger').removeClass('alert-warning')
  },
  methods: {
    sayWelcome: function () {
      alert('Salut !')
    },
    forgotPassword: function () {
      $('form').hide()
      setTimeout(function () {
        $('form').show()
      }, 500);
    }
  }
}
</script>
