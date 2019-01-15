<template>
  <div class="courses-list">
    <h1>{{ translations.LIST_COURSES }}</h1>
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
        <td class="first">{{ course.name }}
          <span v-show="course.assigned">{{ translations.PARTICIPATING }}</span>
        </td>
        <td>{{ `${course.teacher['first-name']} ${course.teacher['last-name']}` }}</td>
        <td>{{ course.count }}</td>
      </tr>
    </table>
  </div>
</template>

<script>
export default {
  name: 'CoursesList',
  data() {
    return {
      courses: [],
    };
  },
  async created() {
    const { courses } = await this.$api.get('/students/courses')
      .then(({ data }) => data)
      .catch(err => console.log(err)); // eslint-disable-line

    this.courses = courses;
  },
  methods: {
    goToCourse(course) {
      if (!this.$store.getters.isStudent) {
        this.$router.push({
          name: 'course-detail',
          params: {
            id: course.id,
          },
        });
      }
    },
  },
};
</script>

<style lang="scss" scoped>
  .courses-list {
    width: 80%;
    margin: auto;

    > h1 {
      font-size: 2rem;
      text-align: left;
    }
  }
</style>
