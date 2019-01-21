<template>
  <div class="grade-list">
    <table>
      <thead>
        <tr>
          <td v-if="showName">{{ translations.FULL_NAME }}</td>
          <td v-if="showCourse">{{ translations.COURSE }}</td>
          <td>{{ translations.GRADE }}</td>
          <td>{{ translations.COMMENT }}</td>
        </tr>
      </thead>
      <tr v-for="(grade, index) in grades" :key="index">
        <td v-if="showName">{{ fullName(grade.student) }}</td>
        <td v-if="showCourse">{{ grade.course.name }}</td>
        <td>{{ grade.value }}</td>
        <td>{{ grade.comment }}</td>
      </tr>
    </table>
  </div>
</template>

<script>
export default {
  name: 'GradeList',
  props: {
    grades: {
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
  },
  computed: {
    showName() {
      const disabled = [
        'student-detail',
      ];

      return !disabled.includes(this.$route.name);
    },
    showCourse() {
      const disabled = [
        'teacher-course-detail',
      ];

      return !disabled.includes(this.$route.name);
    },
  },
};
</script>

<style lang="scss" scoped>
  .grade-list {

  }
</style>
