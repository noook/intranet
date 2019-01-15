<template>
  <div class="course-detail">
    <h1>{{ translations.COURSE }}</h1>
    <section v-if="loaded">
      <div class="header">
        <p>{{ translations.NAME }}: {{ course.name }}</p>
        <p>{{ translations.TEACHER }}:
          <span v-if="course.teacher">{{ fullName(course.teacher) }}</span>
          <b v-else>{{ translations.NONE_YET }}</b>
        </p>
        <div class="assign-teacher">
          <div
            @click="assigning = true"
            v-if="!assigning"
            class="button">{{ translations.ASSIGN_A_TEACHER }}</div>
            <UserSearch
              :type="'teacher'"
              v-else
              @select="assign($event)"
              @close="assigning = false"/>
        </div>
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
      <StudentList
        :students="participating"
        v-if="tab === 'students'"/>
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
import StudentList from '@/components/Student/List.vue';
import UserSearch from '@/components/Input/UserSearch.vue';

export default {
  name: 'CourseDetail',
  components: {
    StudentList,
    UserSearch,
  },
  data() {
    return {
      loaded: false,
      assigning: false,
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
    async assign(user) {
      const { course } = await this.$api.put(`/courses/${this.$route.params.id}/assign`, {
        teacher: user.id,
      })
        .then(({ data }) => data)
        .catch(err => console.log(err)); // eslint-disable-line
      this.assigning = false;
      this.course = course;
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

        > .assign-teacher {
          margin: 10px 0;
          @include d-flex-centered(flex-start);

          > .button {
            padding: 5px 10px;
            border-radius: 5px;
            color: #fff;
            background-color: rgba($flatGreen, .9);

            &:hover {
              cursor: pointer;
            }
          }
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
