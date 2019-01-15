<template>
  <div class="new-course">
    <h2>{{ translations.NEW_COURSE }}</h2>
    <form @submit.prevent="submit" >
      <label for="course-name">{{ translations.COURSE_NAME }}:</label>
      <input id="course-name" v-model="name" type="text">
      <div @click="submit">{{ translations.ADD_COURSE }}</div>
    </form>
  </div>
</template>

<script>
export default {
  name: 'AdminCoursesNew',
  data() {
    return {
      name: '',
    };
  },
  methods: {
    submit() {
      this.$api.post('/courses/new', {
        name: this.name,
      })
        .then(({ data }) => {
          this.$router.push({
            name: 'course-detail',
            params: {
              id: data.id,
            },
          });
        })
        .catch(err => console.log(err)); // eslint-disable-line
    },
  },
};
</script>

<style lang="scss" scoped>
  .new-course {

    > h2 {
      font-size: 1.5rem;
      text-align: left;
    }
    > form {
      display: flex;
      flex-direction: column;
      align-items: flex-start;

      > label, input, div {
        margin: 10px 0;
      }
      > input[type="text"] {
        all: inherit;
        border-radius: 5px;
        border: solid 1px rgba($flatBlack, .4);
        padding: 5px 10px;
        text-align: left;
      }
      > div {
        border-radius: 5px;
        padding: 5px 10px;
        background-color: rgba($flatGreen, 0.9);
        color: #fff;
        &:hover {
          cursor: pointer;
        }
      }
    }
  }
</style>
