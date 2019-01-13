<template>
  <div class="teachers-list">
    <div class="header"></div>
    <table>
      <thead>
        <tr>
          <td>{{ translations.ID }}</td>
          <td>{{ translations.TEACHER }}</td>
          <td>{{ translations.FORM_EMAIL }}</td>
          <td>{{ translations.CLASSES }}</td>
        </tr>
      </thead>
      <tr v-for="(teacher, index) in teachers" :key="index">
        <td>{{ teacher.id }}</td>
        <td>{{ `${teacher['first-name']} ${teacher['last-name']}` }}</td>
        <td>{{ teacher.email }}</td>
        <td>{{ teacher.classes }}</td>
      </tr>
    </table>
  </div>
</template>

<script>
export default {
  name: 'TeachersList',
  data() {
    return {
      teachers: [],
    };
  },
  async created() {
    const { teachers } = await this.$api.get('/users/teachers')
      .then(({ data }) => data)
      .catch(err => console.log(err)); // eslint-disable-line

    this.teachers = teachers;
  },
};
</script>

<style lang="scss" scoped>
  .teachers-list {
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
