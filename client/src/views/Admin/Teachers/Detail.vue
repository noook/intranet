<template>
  <div class="teacher-detail">
    <section class="header" v-if="loaded">
      <h2>{{ translations.TEACHER }}</h2>
      <p>{{ translations.FORM_FIRST_NAME }}: {{ teacher['first-name'] }}</p>
      <p>{{ translations.FORM_LAST_NAME }}: {{ teacher['last-name'] }}</p>
      <p>{{ translations.FORM_EMAIL }}:
        <a :href="`mailto:${teacher.email}`">{{ teacher.email }}</a>
      </p>
    </section>
    <hr>
    <section class="content">
      <CourseList :courses="classes" />
    </section>
  </div>
</template>

<script>
import CourseList from '@/components/Course/List.vue';

export default {
  name: 'TeacherDetail',
  components: {
    CourseList,
  },
  data() {
    return {
      loaded: false,
      teacher: null,
      classes: [],
    };
  },
  async created() {
    const { teacher, classes } = await this.$api.get(`/teachers/${this.$route.params.id}`)
      .then(({ data }) => data)
      .catch(err => console.log(err)); // eslint-disable-line

    this.teacher = teacher;
    this.classes = classes;
    this.loaded = true;
  },
};
</script>

<style lang="scss" scoped>
  .teacher-detail{
    > h2 {
      font-size: 1.5rem;
      text-align: left;
    }

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
    }
  }
</style>
