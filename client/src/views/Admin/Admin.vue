<template>
  <div class="admin-panel">
    <h1>{{ translations.ADMIN_PANEL }}</h1>
    <ul>
      <li>
        <router-link to="/admin/users">{{ translations.LIST_ROLE_MANAGEMENT }}</router-link>
      </li>
      <li>
        <router-link to="/admin/students">{{ translations.LIST_STUDENTS }}</router-link>
      </li>
      <li>
        <router-link to="/">{{ translations.LIST_TEACHERS }}</router-link>
      </li>
      <li>
        <router-link to="/admin/courses">{{ translations.LIST_COURSES }}</router-link>
      </li>
    </ul>
    <section>
      <router-view></router-view>
    </section>
  </div>
</template>
<script>
export default {
  name: 'AdminPanel',
};
</script>

<style lang="scss" scoped>
  .admin-panel {
    width: 80%;
    margin: auto;

    > h1 {
      font-size: 2rem;
      text-align: left;
    }

    > ul {
      margin: 15px 0;
      @include d-flex-centered(flex-start);
      > li {
        margin-right: 10px;

        > a {
          font-size: 1.2rem;
          color: $flatBlue;

          &.router-link-exact-active {
            text-decoration: underline;
            font-weight: 500;
          }

          &:hover {
            cursor: pointer;
            text-decoration: underline;
          }
        }
      }
    }

    > section {
      margin: 15px 0;
    }
  }
</style>
