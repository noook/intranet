<template>
  <div class="student-grades-list">
    <h1>{{ translations.GRADES }}</h1>
    <p v-if="grades.length">{{ translations.AVERAGE }}: {{ calcAverage }} / 20</p>
    <GradesList :grades="sortGrades"/>
  </div>
</template>

<script>
import GradesList from '@/components/Grade/List.vue';

export default {
  name: 'StudentCourses',
  components: {
    GradesList,
  },
  data() {
    return {
      grades: [],
    };
  },
  async created() {
    const { grades } = await this.$api.get('/student/grades')
      .then(({ data }) => data)
      .catch(err => console.log(err)); // eslint-disable-line

    this.grades = grades;
  },
  computed: {
    sortGrades() {
      return this.grades.concat().sort((a, b) => {
        if (a.course.name > b.course.name) {
          return 1;
        }
        if (a.course.name < b.course.name) {
          return -1;
        }
        return 0;
      });
    },
    calcAverage() {
      const average = this.grades.reduce((acc, item) => acc += item.value, 0) / this.grades.length; // eslint-disable-line
      return Math.round(average * 100) / 100;
    },
  },
};
</script>

<style lang="scss" scoped>
  .student-grades-list {
    width: 80%;
    margin: auto;

    > h1 {
      font-size: 2rem;
      text-align: left;
    }

    > p {
      text-align: left;
    }
  }
</style>
