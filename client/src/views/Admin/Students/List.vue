<template>
  <div class="students-list">
    <div class="header"></div>
    <StudentList :students="students" />
  </div>
</template>

<script>
import StudentList from '@/components/Student/List.vue';

export default {
  name: 'AdminStudentsList',
  components: {
    StudentList,
  },
  data() {
    return {
      students: [],
    };
  },
  async created() {
    const { students } = await this.$api.get('/users/students')
      .then(({ data }) => data)
      .catch(err => console.log(err)); // eslint-disable-line

    this.students = students;
  },
  methods: {},
};
</script>

<style lang="scss" scoped>
  .students-list {
    margin: auto;
    > h1 {
      font-size: 1.8rem;
      text-align: left
    }

    > table {
      width: 100%;
      margin: 10px 0;
      border-collapse: collapse;

      > thead {
        text-align: left;
        color: rgba($flatBlack, .4);
        background-color: #efefef;
        font-weight: 500;
      }

      td {
        padding: 10px 30px
      }

      > tr {
        &:nth-child(odd) {
          background-color: #f4f4f4;
        }
        &:nth-child(even) {
          background-color: #fff;
        }
        &:hover {
          background-color: #f2f2f2;
          cursor: pointer;
        }

        td {
          text-align: left;

          &.center {
            @include d-flex-centered(center);
          }
        }
      }
    }
  }
</style>
