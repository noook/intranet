<template>
  <div class="authentication">
    <div class="form-container">
      <Login v-if="path === 'login'"/>
      <Register v-if="path === 'register'"/>
    </div>
  </div>
</template>

<script>
import Register from '@/components/Auth/Register.vue';
import Login from '@/components/Auth/Login.vue';

export default {
  components: {
    Register,
    Login,
  },
  data() {
    return {
      path: null,
    };
  },
  mounted() {
    this.path = this.$route.params.pathMatch;
  },
};
</script>

<style lang="scss" scoped>
  .authentication {
    width: 100%;
    height: 100vh;
    background: linear-gradient(6deg, #d46763, #8766bc);
    @include d-flex-centered(center);

    > .form-container {
      background-color: #fff;
      border-radius: 5px;
      padding: 50px;
    }
  }
</style>
