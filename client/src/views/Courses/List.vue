<template>
  <div class="student-courses-list">
    <h1>{{ translations.LIST_COURSES }}</h1>
    <CourseList :courses="courses" />
  </div>
</template>

<script>
import CourseList from '@/components/Course/List.vue';

export default {
  name: 'CoursesList',
  components: {
    CourseList,
  },
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
  },
};
</script>

<style lang="scss" scoped>
  .student-courses-list {
    width: 80%;
    margin: auto;

    > h1 {
      font-size: 2rem;
      text-align: left;
    }
  }
</style>
