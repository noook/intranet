<template>
  <div class="course-list">
    <table>
      <thead>
        <tr>
          <td>{{ translations.ID }}</td>
          <td>{{ translations.FULL_NAME }}</td>
          <td>{{ translations.FORM_EMAIL }}</td>
          <td v-if="showClasses">{{ translations.CLASSES }}</td>
        </tr>
      </thead>
      <tr
        @click="goToStudent(student.id)"
        v-for="(student, index) in students"
        :key="index">
        <td>{{ student.id }}</td>
        <td>{{ fullName(student) }}</td>
        <td>{{ student.email }}</td>
        <td v-if="showClasses">{{ student.courses }}</td>
      </tr>
    </table>
  </div>
</template>

<script>
export default {
  name: 'StudentList',
  props: {
    students: {
      type: Array,
      default() {
        return [];
      },
    },
  },
  methods: {
    fullName(user) {
      return `${user['first-name']} ${user['last-name']}`;
    },
    goToStudent(id) {
      this.$router.push({
        name: 'student-detail',
        params: {
          id,
        },
      });
    },
  },
  computed: {
    showClasses() {
      const disabled = [
        'course-detail',
      ];

      return !disabled.includes(this.$route.name);
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
