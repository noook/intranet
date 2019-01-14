<template>
  <div class="admin-student-detail">
    <div v-for="(user, index) in users" :key="index">
      {{ fullName(user) }}
    </div>
  </div>
</template>

<script>
export default {
  name: 'AdminStudentDetail',
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
  },
};
</script>

<style lang="scss" scoped>

</style>
