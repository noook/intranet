<template>
  <div class="admin-course-manage-student">
    <h1>{{ translations.COURSE_MANAGEMENT }}</h1>
    <section v-if="loaded">
      <div class="header">
        <p>{{ translations.NAME }}: {{ course.name }}</p>
        <p>{{ translations.TEACHER }}:
          <span v-if="course.teacher">{{ fullName(course.teacher) }}</span>
          <b v-else>{{ translations.NONE_YET }}</b>
        </p>
      </div>
      <hr>
      <div class="panel">
        <div class="participating">
          <ul>
            <li v-for="student in participating" :key="student.id">
              <img
                @click="update(student, 'remove')"
                src="@/assets/img/minus-red.png" alt="delete">
              <div>{{ fullName(student) }}</div>
            </li>
          </ul>
        </div>
        <div class="separator"></div>
        <div class="search">
          <input :placeholder="translations.SEARCH_PLACEHOLDER" v-model="search" type="text">
          <ul>
            <li v-for="student in filteredList" :key="student.id">
              <img
                @click="update(student, 'add')"
                src="@/assets/img/plus-green.png" alt="delete">
              <div>{{ fullName(student) }}</div>
            </li>
          </ul>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  name: 'AdminCourseManageStudent',
  data() {
    return {
      search: '',
      course: null,
      participating: [],
      loaded: false,
      students: [],
    };
  },
  async created() {
    const { course, participating } = await this.$api.get(`/courses/${this.$route.params.id}`)
      .then(({ data }) => data)
      .catch(err => console.log(err)); // eslint-disable-line

    const { students } = await this.$api.get('/users/students')
      .then(({ data }) => data)
      .catch(err => console.log(err)); // eslint-disable-line

    this.course = course;
    this.participating = participating;
    this.students = students;
    this.loaded = true;
  },
  methods: {
    fullName(user) {
      return `${user['first-name']} ${user['last-name']}`;
    },
    async update(user, action) {
      const { participating } = await this.$api.put(`/students/courses/${this.course.id}/participating/${user.id}`, {
        action,
      })
        .then(({ data }) => data)
        .catch(err => console.log(err)); // eslint-disable-line

      this.participating = participating;
    },
  },
  computed: {
    participatingIds() {
      return this.participating.map(student => student.id);
    },
    filteredList() {
      const nameFiltered = this.students.filter((student) => {
        const fullName = this.fullName(student).toLowerCase();
        return fullName.includes(this.search.toLowerCase());
      });

      return nameFiltered.filter(student => !this.participatingIds.includes(student.id));
    },
  },
};
</script>

<style lang="scss" scoped>
  .admin-course-manage-student {
    width: 80%;
    margin: auto;

    > h1 {
      margin: 20px 0;
      font-size: 2rem;
      text-align: left;
    }

    section {
      > .header {
        p {
          text-align: left;
        }
      }

      > .panel {
        display: flex;
        justify-content: space-between;

        > .separator {
          width: 1px;
          background-color: rgba($flatBlack, .2);
        }

        > .participating, .search {
          width: 50%;
          padding: 10px;
        }

        > .participating {
          > ul {
            > li {
              text-align: left;
              margin: 5px 0;
              @include d-flex-centered(flex-start);

              > img {
                margin-right: 5px;
                width: 14px;
                height: 14px;
                &:hover {
                  cursor: pointer;
                }
              }
            }
          }
        }

        > .search {
          > input {
            width: 80%;
            border-radius: 5px;
            font-family: $defaultFont;
            font-size: 1rem;
            border: solid 1px rgba($flatBlack, .2);
            padding: 5px 10px;
          }

          > ul {
            width: 80%;
            margin: 10px auto;
            > li {
              text-align: left;
              margin: 5px 0;
              @include d-flex-centered(flex-start);

              > img {
                margin-right: 5px;
                width: 14px;
                height: 14px;
                &:hover {
                  cursor: pointer;
                }
              }
            }
          }
        }
      }
    }
  }
</style>
