<template>
  <div class="courses-list">
    <div class="header">
      <router-link
        class="add-course-btn"
        to="/admin/courses/new">{{ translations.ADD_COURSE }}</router-link>
    </div>
    <CourseList :courses="courses" />
  </div>
</template>

<script>
import CourseList from '@/components/Course/List.vue';

export default {
  name: 'CoursesList',
  components: {
    CourseList,
  },
  data() {
    return {
      courses: [],
    };
  },
  async created() {
    const { courses } = await this.$api.get('/courses')
      .then(({ data }) => data)
      .catch(err => console.log(err)); // eslint-disable-line

    this.courses = courses;
  },
};
</script>

<style lang="scss" scoped>
  .courses-list {
    margin: auto;
    > h1 {
      font-size: 1.8rem;
      text-align: left
    }

    > .header {
      @include d-flex-centered(flex-start);

      .add-course-btn {
        border-radius: 5px;
        padding: 5px 10px;
        background-color: rgba($flatGreen, .9);
        color: #fff;
        &:hover {
          cursor: pointer;
        }
      }
    }
  }
</style>
