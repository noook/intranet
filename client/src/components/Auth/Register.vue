<template>
  <div class="register">
    <form @submit.prevent="register">
      <h2>{{ translations.REGISTER }}</h2>
      <div class="login-inputs">
        <input
          type="text"
          v-model="form.firstName"
          name="first-name"
          :placeholder="translations.FORM_FIRST_NAME">
        <input
          type="text"
          v-model="form.lastName"
          name="last-name"
          :placeholder="translations.FORM_LAST_NAME">
        <input
          type="email"
          v-model="form.email"
          name="email"
          :placeholder="translations.FORM_EMAIL">
        <small v-if="registerError.email">{{ translations.ERROR_EMAIL_TAKEN }}</small>
        <input
          type="password"
          v-model="form.password"
          name="password"
          :placeholder="translations.FORM_PASSWORD">
        <input
          type="password"
          v-model="form.passwordConfirmation"
          name="password-confirmation"
          :placeholder="translations.FORM_PASSWORD_REPEAT">
        <small v-if="registerError.password">{{ translations.ERROR_PASSWORDS_NO_MATCH }}</small>
      </div>
      <button type="submit" class="login-submit">{{ translations.REGISTER }}</button>
    </form>
  </div>
</template>

<script>
/* eslint-disable no-restricted-syntax, guard-for-in */
import axios from 'axios';

export default {
  name: 'register',
  data() {
    return {
      form: {
        firstName: null,
        lastName: null,
        email: null,
        password: null,
        passwordConfirmation: null,
      },
      registerError: {
        email: false,
        password: false,
      },
    };
  },
  methods: {
    async register() {
      for (const i in this.registerError) {
        this.registerError[i] = false;
      }
      const {
        firstName,
        lastName,
        email,
        password,
        passwordConfirmation,
      } = this.form;

      const baseUrl = process.env.NODE_ENV === 'development' ? 'http://intranet.ml' : 'https://api.intranet.nook.sh';
      const { token, expiracy } = await axios.post(`${baseUrl}/register`, {
        firstName,
        lastName,
        email,
        password,
        passwordConfirmation,
      })
        .then(({ data }) => data) // eslint-disable-line
        .catch((err) => {
          for (const i in err.response.data.errors) {
            this.registerError[i] = err.response.data.errors[i];
          }
        });
      this.$store.dispatch('setToken', { token, expiracy });
      this.$router.push('/');
    },
  },
};
</script>

<style lang="scss" scoped>
.register {
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
