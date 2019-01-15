<template>
  <div class="admin-student-detail">
    <section class="header" v-if="loaded">
      <h2>{{ translations.STUDENT }}</h2>
      <p>{{ translations.FORM_FIRST_NAME }}: {{ student['first-name'] }}</p>
      <p>{{ translations.FORM_LAST_NAME }}: {{ student['last-name'] }}</p>
      <p>{{ translations.FORM_EMAIL }}:
        <a :href="`mailto:${student.email}`">{{ student.email }}</a>
      </p>
    </section>
    <hr>
    <section class="tabs">
      <ul>
        <li
          :class="{active: tab === 'courses'}"
          @click="tab = 'courses'">{{ translations.COURSES }}</li>
        <li
          :class="{active: tab === 'grades'}"
          @click="tab = 'grades'">{{ translations.GRADES }}</li>
      </ul>
    </section>
    <section class="tabs-content">
      <CourseList :courses="courses" v-if="tab === 'courses'" />
      <GradeList :grades="grades" v-if="tab === 'grades'" />
    </section>
  </div>
</template>

<script>
import GradeList from '@/components/Grade/List.vue';
import CourseList from '@/components/Course/List.vue';

export default {
  name: 'AdminStudentDetail',
  components: {
    GradeList,
    CourseList,
  },
  data() {
    return {
      tab: 'courses',
      loaded: false,
      student: null,
      grades: [],
      courses: [],
    };
  },
  async created() {
    const { student, grades, courses } = await this.$api.get(`/users/${this.$route.params.id}`)
      .then(({ data }) => data)
      .catch(err => console.log(err)) // eslint-disable-line

    this.student = student;
    this.grades = grades;
    this.courses = courses;
    this.loaded = true;
  },
};
</script>

<style lang="scss" scoped>
  .admin-student-detail {
    > section {
      &.header {
        text-align: left;
        > h2 {
          font-size: 1.5rem;
        }

        a {
          color: $flatBlue;
        }
      }

      &.tabs {
        > ul {
          @include d-flex-centered(flex-start);

          > li {
            margin-right: 10px;
            color: $flatBlue;

            &.active {
              text-decoration: underline;
            }

            &:hover {
              text-decoration: underline;
              cursor: pointer;
            }
          }
        }
      }
    }
  }
</style>
