<template>
  <div class="teacher-panel">
    <h1>{{ translations.TEACHER_PANEL }}</h1>
    <section>
      <CourseList :courses="courses" />
    </section>
  </div>
</template>

<script>
import CourseList from '@/components/Course/List.vue';

export default {
  name: 'TeacherPanel',
  components: {
    CourseList,
  },
  data() {
    return {
      teacher: null,
      courses: [],
    };
  },
  async created() {
    const { courses } = await this.$api.get('/teacher/courses')
      .then(({ data }) => data)
      .catch(err => console.log(err)); // eslint-disable-line
    this.courses = courses;
  },
};
</script>

<style lang="scss" scoped>
  .teacher-panel {
    width: 80%;
    margin: auto;

    > h1 {
      font-size: 2rem;
      text-align: left;
    }

    section {
      margin: 30px 0;
    }
  }
</style>
