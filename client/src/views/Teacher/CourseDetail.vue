<template>
  <div class="teacher-course-detail">
    <h1>{{ translations.COURSE }}</h1>
    <div class="header" v-if="loaded">
      <p>{{ translations.NAME }}: {{ course.name }}</p>
    </div>
    <hr>
    <section v-if="loaded" class="tabs">
      <ul>
        <li
          @click="selected = 'students'"
          :class="{ selected: selected === 'students' }">{{ translations.STUDENTS }}</li>
        <li
          @click="selected = 'grades'"
          :class="{ selected: selected === 'grades' }">{{ translations.GRADES }}</li>
      </ul>
    </section>
    <section v-if="loaded" class="lists">
      <StudentList v-if="selected === 'students'" :students="students" />
      <GradeList v-if="selected === 'grades'" :grades="grades" />
    </section>
    <img v-if="!loaded" src="@/assets/svg/loading.svg" alt="">
  </div>
</template>

<script>
import StudentList from '@/components/Student/List.vue';
import GradeList from '@/components/Grade/List.vue';

export default {
  name: 'TeacherCourseDetail',
  components: {
    StudentList,
    GradeList,
  },
  data() {
    return {
      grades: [],
      course: null,
      students: [],
      selected: 'students',
      loaded: false,
    };
  },
  async created() {
    const { students, grades, course } = await this.$api.get(`/teacher/courses/${this.$route.params.id}`)
      .then(({ data }) => data)
      .catch(err => console.log(err)); // eslint-disable-line

    this.course = course;
    this.students = students;
    this.grades = grades;
    this.loaded = true;
  },
};
</script>

<style lang="scss" scoped>
  .teacher-course-detail {
    width: 80%;
    margin: auto;

    > h1 {
      font-size: 2rem;
      margin: 15px 0;
      text-align: left;
    }

    > .header {
      > p {
        text-align: left;
        font-size: 1.2rem;
      }
    }

    > section {
      &.tabs {
        > ul {
          @include d-flex-centered(flex-start);
          > li {
            margin-right: 10px;

            &.selected {
              color: $flatBlue;
              text-decoration: underline;
            }

            &:hover {
              cursor: pointer;
              color: $flatBlue;
              text-decoration: underline;
            }
          }
        }
      }
    }
  }
</style>
