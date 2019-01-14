<template>
    <div class="new-course">
        <form @submit.prevent="submit" >
            <label for="">{{ translations.COURSE_NAME }}</label>
            <input v-model="name" type="text">
            <div @click="submit">{{ translations.NEW_COURSE }}</div>
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
        .then(({ data }) => data)
        .catch(err => console.log(err)); // eslint-disable-line
    },
  },
};
</script>

<style lang="scss" scoped>
    .new-course {
        > form {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            > label, input, div {
                margin-top: 30px;
            }
            > input[type="text"] {
                all: inherit;
                border-radius: 20px;
                background-color: #f5f5f5;
                padding: 5px 20px;
                margin: 5px 0;
                text-align: left;
                margin-top: 20px;
            }
            > div {
                display: inline;
                border-radius: 5px;
                padding: 5px 10px;
                background-color: rgba(46, 204, 113, 0.9);
                color: #fff;
                &:hover {
                    cursor: pointer;
                }
            }
        }
    }
</style>
