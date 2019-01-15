<template>
  <div class="course-list">
    <table>
      <thead>
        <tr>
          <td>{{ translations.NAME }}</td>
          <td v-if="showTeacher">{{ translations.TEACHER }}</td>
          <td>{{ translations.PARTICIPANTS }}</td>
          <td class="center" v-if="$store.getters.isStudent">{{ translations.ENROLL }}</td>
        </tr>
      </thead>
      <tr
        @click="goToCourse(course)"
        v-for="(course, index) in courses"
        :key="index">
        <td class="first">
          {{ course.name }}
          <span v-show="course.assigned">{{ translations.PARTICIPATING }}</span>
        </td>
        <td v-if="showTeacher">{{ fullName(course.teacher) }}</td>
        <td>{{ course.count }}</td>
        <td class="center" v-if="$store.getters.isStudent">
          <div class="button"
            :class="{
              red: course.assigned,
              green: !course.assigned
            }"
            @click="updateParticipation(course)"
          >{{ course.assigned ? translations.DISENROLL : translations.ENROLL }}</div>
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
export default {
  name: 'CourseList',
  props: {
    courses: {
      type: Array,
      default() {
        return [];
      },
    },
  },
  methods: {
    fullName(user) {
      return `${user['first-name']} ${user['last-name']}`;
    },
    goToCourse(course) {
      if (!this.$store.getters.isStudent) {
        this.$router.push({
          name: 'course-detail',
          params: {
            id: course.id,
          },
        });
      }
    },
    async updateParticipation(course) {
      const { enrolled, updated } = await this.$api.put(`/students/courses/participating/${course.id}`)
        .then(({ data }) => data)
        .catch(err => console.log(err)); // eslint-disable-line

      course.count = updated.count; // eslint-disable-line
      course.assigned = enrolled; // eslint-disable-line
    },
  },
  computed: {
    showTeacher() {
      const disabled = [
        'teacher-detail',
      ];

      return !disabled.includes(this.$route.name);
    },
  },
};
</script>

<style lang="scss" scoped>
  .course-list {
    table {
      > tr {
        > td {
          > .button {
            border-radius: 5px;
            padding: 5px 10px;
            color: #fff;

            &.red {
              background-color: rgba($flatRed, .9);
            }

            &.green {
              background-color: rgba($flatGreen, .9);
            }
          }
        }
      }
    }
  }
</style>
