<template>
  <div class="courses-list">
    <table>
      <thead>
        <tr>
          <td>{{ translations.NAME }}</td>
          <td>{{ translations.TEACHER }}</td>
          <td>{{ translations.PARTICIPANTS }}</td>
        </tr>
      </thead>
      <tr
        @click="goToCourse(course)"
        v-for="(course, index) in courses"
        :key="index">
        <td>{{ course.name }}</td>
        <td>{{ fullName(course.teacher) }}</td>
        <td>{{ course.count }}</td>
      </tr>
    </table>
  </div>
</template>

<script>
export default {
  name: 'CourseList',
  props: {
    courses: {
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
    goToCourse({ id }) {
      this.$router.push({
        name: 'course-detail',
        params: {
          id,
        },
      });
    },
  },
  computed: {
  },
};
</script>

<style lang="scss" scoped>
  .courses-list {

  }
</style>
