<template>
  <div class="login">
    <form @submit.prevent="login">
      <h2>{{ translations.LOGIN }}</h2>
      <div class="login-inputs">
        <input
          type="text"
          v-model="form.email"
          name="email"
          id="email"
          :placeholder="translations.FORM_EMAIL">
        <input
          type="password"
          v-model="form.password"
          name="password"
          id="user-password"
          :placeholder="translations.FORM_PASSWORD">
          <small v-if="loginError">{{ translations.INVALID_EMAIL_OR_PASSWORD }}</small>
      </div>
      <button type="submit" class="login-submit">{{ translations.LOGIN }}</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Login',
  data() {
    return {
      form: {
        email: null,
        password: null,
      },
      loginError: false,
    };
  },
  methods: {
    async login() {
      this.loginError = false;
      const { email, password } = this.form;
      const baseUrl = process.env.NODE_ENV === 'development' ? 'http://intranet.ml' : 'https://api.intranet.nook.sh';
      const { token } = await axios.post(`${baseUrl}/login`, {
        email,
        password,
      })
        .then(({ data }) => data)
        .catch(() => { this.loginError = true; });

      this.$store.dispatch('setToken', { token });
      this.$router.push('/');
    },
  },
  components: {},
};
</script>

<style lang="scss" scoped>
.login {
  > form {
    > h2 {
      font-size: 2rem;
      margin-bottom: 15px;
    }

    > .login-inputs {
      display: flex;
      flex-direction: column;
      margin: 15px 0;

      > input {
        all: inherit;
        border-radius: 20px;
        background-color: #f5f5f5;
        padding: 5px 20px;
        margin: 5px 0;
        text-align: left;
      }

      > small {
        text-align: left;
        color: #d91e18;
        padding: 0 5px;
      }
    }

    > .login-submit {
      all: inherit;
      background-color: #2ECC71;
      box-sizing: border-box;
      border-radius: 20px;
      padding: 5px 20px;
      color: #fff;
      width: 100%;
      font-weight: bold;

      &:hover {
        cursor: pointer;
      }
    }
  }
}
</style>
