import Vue from 'vue'

import LoginForm from "@/base/modules/auth/components/LoginForm"
import HelloWorld from "@/base/modules/auth/components/HelloWorld"

import './index.css'

new Vue({
  el: '#auth_index',
  components: {
    HelloWorld,
    LoginForm
  }
})