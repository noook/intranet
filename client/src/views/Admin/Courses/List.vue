<template>
  <div class="courses-list">
    <div class="header"></div>
    <table>
      <thead>
        <tr>
          <td>{{ translations.NAME }}</td>
          <td>{{ translations.TEACHER }}</td>
          <td>{{ translations.PARTICIPANTS }}</td>
        </tr>
      </thead>
      <tr v-for="(course, index) in courses" :key="index">
        <td>{{ course.name }}</td>
        <td>{{ `${course.teacher['first-name']} ${course.teacher['last-name']}` }}</td>
        <td>{{ course.count }}</td>
      </tr>
    </table>
  </div>
</template>

<script>
export default {
  name: 'CoursesList',
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
