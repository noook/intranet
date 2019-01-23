<template>
  <div class="grade-list">
    <table>
      <thead>
        <tr>
          <td v-if="showName">{{ translations.FULL_NAME }}</td>
          <td v-if="showCourse">{{ translations.COURSE }}</td>
          <td>{{ translations.GRADE }}</td>
          <td>{{ translations.COMMENT }}</td>
          <td class="center" v-if="$store.getters.isAdmin">{{ translations.EDIT }}</td>
        </tr>
      </thead>
      <tr v-for="(grade, index) in grades" :key="index">
        <td v-if="showName">{{ fullName(grade.student) }}</td>
        <td v-if="showCourse">{{ grade.course.name }}</td>
        <td>{{ grade.value }}</td>
        <td>{{ grade.comment }}</td>
        <td class="center edit" v-if="$store.getters.isAdmin">
          <img
            class="edit"
            @click="editGrade(grade)"
            src="@/assets/img/edit.png"
            alt="edit">
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
export default {
  name: 'GradeList',
  props: {
    grades: {
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
    editGrade(grade) {
      if (this.$store.getters.isAdmin) {
        this.$router.push({
          name: 'admin-grade-edit',
          params: {
            student: grade.student.id,
            grade: grade.id,
          },
        });
      }
    },
  },
  computed: {
    showName() {
      const disabled = [
        'student-detail',
        'student-grades-list',
      ];

      return !disabled.includes(this.$route.name);
    },
    showCourse() {
      const disabled = [
        'teacher-course-detail',
        'course-detail',
      ];

      return !disabled.includes(this.$route.name);
    },
  },
};
</script>

<style lang="scss" scoped>
  .grade-list {
    td > img.edit {
      display: none;
      width: 20px;
      height: 20px;
    }

    tr:hover {
      td.edit > img {
        display: block;
      }
    }
  }
</style>
