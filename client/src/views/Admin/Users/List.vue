<template>
  <div class="admin-users-list">
    <div class="save-actions">
      <div
        @click="saveRoles"
        class="button-save">{{ translations.SAVE }}</div>
      <div :class="{ show: saved }" class="success">
        <img src="@/assets/svg/check-green.svg" alt="Green check">
        {{ translations.SAVED }}</div>
    </div>
    <table>
      <thead>
        <tr>
          <td>{{ translations.ID }}</td>
          <td>{{ translations.FULL_NAME }}</td>
          <td>{{ translations.FORM_EMAIL }}</td>
          <td class="center">{{ translations.ROLE }}</td>
        </tr>
      </thead>
      <tr v-for="(user, index) in users" :key="index">
        <td>{{ user.id }}</td>
        <td>{{ fullName(user) }}</td>
        <td>{{ user.email }}</td>
        <td class="center">
          <select
            :disabled="user.email === $store.state.USER"
            name="role"
            id="role"
            v-model="selectedRoles[user.id]">
            <option
              v-for="(role, index) in roles"
              :key="index"
              :value="role"
              :selected="getRole(user) === role">{{ translations[role] }}</option>
          </select>
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
export default {
  name: 'AdminUserList',
  data() {
    return {
      saved: false,
      users: [],
      roles: [],
      selectedRoles: {},
    };
  },
  async created() {
    const { users, roles } = await this.$api.get('/users')
      .then(({ data }) => data)
      .catch(err => console.log(err)) // eslint-disable-line
    users.forEach((user) => {
      this.selectedRoles[user.id] = this.getRole(user);
    });
    this.users = users;
    this.roles = roles;
  },
  methods: {
    fullName(user) {
      return `${user['first-name']} ${user['last-name']}`;
    },
    getRole(user) {
      return user.roles.find(role => role === 'ROLE_ADMIN' || role === 'ROLE_TEACHER' || role === 'ROLE_STUDENT');
    },
    async saveRoles() {
      await this.$api.put('/users/roles', {
        data: this.selectedRoles,
      })
        .catch(err => console.log(err)); // eslint-disable-line

      this.saved = true;
      setTimeout(() => {
        this.saved = false;
      }, 4000);
    },
  },
};
</script>

<style lang="scss" scoped>
  .admin-users-list {
    display: flex;
    flex-direction: column;
    align-items: flex-start;

    > .save-actions {
      @include d-flex-centered(flex-start);
      > .success {
        display: none;
        @keyframes fade {
          0%   { opacity: 1; }
          100% { opacity: 0; }
        }
        img {
          width: 16px;
          height: 16px;
          margin-right: 5px;
        }
        &.show {
          @include d-flex-centered(flex-start);
          display: block;
          animation: fade 3s 1s;
          animation-fill-mode: forwards;
        }
      }
      > .button-save {
        display: inline;
        margin: 10px 0;
        border-radius: 5px;
        padding: 5px 10px;
        margin-right: 15px;
        background-color: rgba($flatGreen, .9);
        color: #fff;
        &:hover {
          cursor: pointer;
        }
      }
    }
  }
</style>
