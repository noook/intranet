<template>
  <div class="course-detail">
    <h1>{{ translations.COURSE }}</h1>
    <section v-if="loaded">
      <div class="header">
        <p>{{ translations.NAME }}: {{ course.name }}</p>
        <p>{{ translations.TEACHER }}: {{ fullName(course.teacher) }}</p>
      </div>
      <hr>
      <div class="tabs">
        <ul>
          <li
            @click="tab = 'students'"
            :class="{active: tab === 'students'}">{{ translations.STUDENTS }}</li>
          <li
            @click="tab = 'grades'"
            :class="{active: tab === 'grades'}">{{ translations.GRADES }}</li>
        </ul>
      </div>
      <table v-if="tab === 'students'">
        <thead>
          <tr>
            <td>{{ translations.ID }}</td>
            <td>{{ translations.FULL_NAME }}</td>
            <td>{{ translations.FORM_EMAIL }}</td>
          </tr>
        </thead>
        <tr v-for="(student, index) in participating" :key="index">
          <td>{{ student.id }}</td>
          <td>{{ `${student['first-name']} ${student['last-name']}` }}</td>
          <td>{{ student.email }}</td>
        </tr>
      </table>
      <table v-if="tab === 'grades'">
        <thead>
          <tr>
            <td>{{ translations.FULL_NAME }}</td>
            <td>{{ translations.GRADE }}</td>
            <td>{{ translations.COMMENT }}</td>
          </tr>
        </thead>
        <tr v-for="(grade, index) in grades" :key="index">
          <td>{{ fullName(grade.student) }}</td>
          <td>{{ grade.value }}</td>
          <td>{{ grade.comment }}</td>
        </tr>
      </table>
    </section>
    <img v-else src="@/assets/svg/loading.svg" alt="Loading">
  </div>
</template>

<script>
export default {
  name: 'CourseDetail',
  data() {
    return {
      loaded: false,
      course: null,
      participating: [],
      grades: [],
      tab: 'students',
    };
  },
  async created() {
    const { course, participating, grades } = await this.$api.get(`/courses/${this.$route.params.id}`)
      .then(({ data }) => data)
      .catch(err => console.log(err)); // eslint-disable-line

    this.course = course;
    this.grades = grades;
    this.participating = participating;
    this.loaded = true;
  },
  methods: {
    fullName(user) {
      return `${user['first-name']} ${user['last-name']}`;
    },
  },
};
</script>

<style lang="scss" scoped>
  .course-detail {

    > h1 {
      font-size: 2rem;
      margin: 15px 0;
      text-align: left;
    }

    > section {
      > .header {
        > p {
          font-size: 1.2rem;
          text-align: left;
        }
      }

      > .tabs {
        > ul {
          @include d-flex-centered(flex-start);
          margin-bottom: 20px;

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
