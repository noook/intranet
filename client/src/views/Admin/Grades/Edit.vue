<template>
  <div class="admin-edit-grade">
    <h1>{{ translations.GRADE_EDIT }}</h1>
    <section v-if="loaded">
      <div class="header">
        <div class="course">
          <h2>{{ translations.COURSE }}</h2>
          <p>{{ translations.NAME }}: {{ grade.course.name }}</p>
          <p>{{ translations.TEACHER }}:
            <span v-if="grade.course.teacher">{{ fullName(grade.course.teacher) }}</span>
            <b v-else>{{ translations.NONE_YET }}</b>
          </p>
        </div>
        <div class="student">
          <h2>{{ translations.STUDENT }}</h2>
          <p>{{ translations.FORM_FIRST_NAME }}: {{ grade.student['first-name'] }}</p>
          <p>{{ translations.FORM_LAST_NAME }}: {{ grade.student['last-name'] }}</p>
          <p>{{ translations.FORM_EMAIL }}:
            <a :href="`mailto:${grade.student.email}`">{{ grade.student.email }}</a>
          </p>
        </div>
        <div class="grade">
          <h2>{{ translations.GRADE }}</h2>
          <p>{{ translations.GRADE }}: {{ grade.value }}</p>
          <p>{{ translations.COMMENT }}: {{ grade.comment }}</p>
        </div>
      </div>
      <div class="content">
        <h2>{{ translations.EDIT }}</h2>
        <form @submit.prevent="submit">
          <input type="number" v-model="edited.value">
          <textarea v-model="edited.comment"></textarea>
          <button type="submit">{{ translations.SAVE }}</button>
        </form>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  name: 'AdminEditGrade',
  data() {
    return {
      grade: null,
      loaded: false,
      edited: {
        value: null,
        comment: null,
      },
    };
  },
  async created() {
    const { grade } = await this.$api.get(`/grade/${this.$route.params.grade}`)
      .then(({ data }) => data)
      .catch(err => console.log(err)); // eslint-disable-line

    this.edited.value = grade.value;
    this.edited.comment = grade.comment;

    this.grade = grade;
    this.loaded = true;
  },
  methods: {
    fullName(user) {
      return `${user['first-name']} ${user['last-name']}`;
    },
    async submit() {
      const { grade } = await this.$api.put(`/admin/grade/${this.grade.id}/edit`, {
        ...this.edited,
      })
        .then(({ data }) => data)
        .catch(err => console.log(err)); // eslint-disable-line

      this.$router.push({
        name: 'course-detail',
        params: {
          id: grade.course.id,
          action: 'grades',
        },
      });
    },
  },
};
</script>

<style lang="scss" scoped>
  .admin-edit-grade {
    width: 80%;
    margin: auto;

    > h1 {
      font-size: 2rem;
      text-align: left;
      margin: 20px 0;
    }

    > section {
      > .header {
        @include d-flex-centered(flex-start);
        align-items: flex-start;

        > div {
          margin-right: 50px;
        }

        h2, p {
          text-align: left;
        }

        h2 {
          font-size: 1.5rem;
        }

        p {
          margin: 5px 0;

          > a {
            color: $flatBlue;
          }
        }
      }

      > .content {
        margin-top: 30px;

        > h2 {
          font-size: 1.5rem;
          text-align: left;
          margin: 10px 0;
        }

        > form {
          display: flex;
          flex-direction: column;
          align-items: flex-start;

          > input {
            border: solid 1px rgba($flatBlack, .5);
            border-radius: 5px;
            padding: 5px 10px;
            font-size: .9rem;
            font-family: $defaultFont;
          }

          > textarea {
            resize: none;
            border: solid 1px rgba($flatBlack, .5);
            border-radius: 5px;
            margin: 10px 0;
            font-family: $defaultFont;
            padding: 5px;
            width: 400px;
            height: 100px;
          }

          > button {
            all: inherit;
            padding: 5px 10px;
            color: #fff;
            border-radius: 5px;
            background-color: rgba($flatGreen, .9);
            &:hover {
              cursor: pointer;
            }
          }
        }
      }
    }
  }
</style>
