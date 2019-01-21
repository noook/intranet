<template>
  <div class="grade-student-action">
    <h1>{{ translations.GRADING }}</h1>
    <section v-if="loaded">
      <div class="header">
        <p>{{ translations.COURSE }} : {{ course.name }}</p>
      </div>
      <form @submit.prevent="submit">
        <div class="group">
          <label for="student">{{ translations.STUDENT }}:</label>
          <select name="student" id="student" v-model="selected.student">
            <option disabled selected :value="null">--------</option>
            <option
              v-for="(student, index) in students"
              :key="index"
              :value="student.id">{{ fullName(student) }}</option>
          </select>
        </div>
        <div class="group">
          <label for="grade">{{ translations.GRADE }}:</label>
          <input id="grade" v-model="selected.value" type="number">
        </div>
        <div class="group">
          <label for="comment">{{ translations.COMMENT }}:</label>
        </div>
        <div class="group">
          <textarea v-model="selected.comment" name="comment" id="comment"></textarea>
        </div>
        <div class="group">
          <button
            :disabled="!isValid"
            type="submit"
            class="submit-btn">{{ translations.SUBMIT }}</button>
        </div>
      </form>
    </section>
  </div>
</template>

<script>
export default {
  name: 'GradeStudentAction',
  data() {
    return {
      loaded: false,
      course: null,
      students: [],
      selected: {
        student: null,
        value: null,
        comment: '',
      },
    };
  },
  async created() {
    const { course, students } = await this.$api.get(`/teacher/courses/${this.$route.params.id}`)
      .then(({ data }) => data)
      .catch(err => console.log(err)); // eslint-disable-line

    this.course = course;
    this.students = students;
    this.loaded = true;
  },
  methods: {
    async submit() {
      await this.$api.post(`/courses/${this.$route.params.id}/grade`, {
        grade: {
          ...this.selected,
          value: parseInt(this.selected.value, 10),
        },
      })
        .then(({ data }) => data)
        .catch(err => console.log(err)); // eslint-disable-line

      this.$router.push({
        name: 'teacher-course-detail',
        params: {
          id: this.$route.params.id,
          action: 'grades',
        },
      });
    },
    fullName(user) {
      return `${user['first-name']} ${user['last-name']}`;
    },
  },
  computed: {
    isValid() {
      return !isNaN(this.selected.value) // eslint-disable-line
      && this.selected.value !== null
      && this.selected.student
      && this.selected.comment.length;
    },
  },
};
</script>

<style lang="scss" scoped>
  .grade-student-action {
    width: 80%;
    margin: auto;

    > h1 {
      text-align: left;
      font-size: 2rem;
    }

    > section {
      > .header {
        > p {
          font-size: 1.2rem;
          text-align: left;
        }
      }

      form {
        display: flex;
        flex-direction: column;
        align-items: flex-start;

        > .group {
          margin: 5px 0;
          @include d-flex-centered(flex-start);

          > input, textarea {
            text-align: left;
            margin: 0;
            border-radius: 5px;
            border: solid 1px rgba($flatBlack, .5);
          }

          > input {
            padding: 3px 5px;
          }

          > textarea {
            padding: 5px 10px;
            resize: none;
            font-family: $defaultFont;
            width: 200px;
            min-height: 100px;
          }

          > label {
            margin-right: 10px;
            min-width: 60px;
            text-align: left;
          }

          .submit-btn {
            all: inherit;
            border-radius: 5px;
            padding: 5px 10px;
            background-color: rgba($flatGreen, .9);
            color: #fff;

            &:disabled {
              background-color: rgba($flatBlack, .3);
            }

            &:enabled:hover {
              cursor: pointer;
            }
          }
        }
      }
    }
  }
</style>
