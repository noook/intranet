<template>
  <div class="user-search">
    <div class="input">
      <img
        @click="$emit('close')"
        src="@/assets/svg/close-red.svg"
        alt="close">
      <input v-model="query" type="text">
      <div
        @click="selected = result"
        class="results"
        :class="{ selected: selected === result }"
        v-for="(result, index) in queryResults" :key="index">
          {{ fullName(result) }}
      </div>
    </div>
    <div
      @click="sendChoice"
      class="validate"
      :class="{ clickable: selected !== null }">
      {{ translations.ASSIGN }}
    </div>
  </div>
</template>

<script>
import { debounce } from 'lodash';

export default {
  name: 'UserSearch',
  data() {
    return {
      selected: null,
      query: null,
      queryResults: [],
    };
  },
  props: {
    type: {
      type: String,
      default: 'all',
    },
  },
  created() {
    this.debouncedSearch = debounce(this.searchUser, 300);
  },
  methods: {
    async searchUser() {
      const { teachers } = await this.$api.get('/users/search', {
        params: {
          query: this.query,
          type: this.type,
        },
      })
        .then(({ data }) => data)
        .catch(err => console.log(err)); // eslint-disable-line

      this.queryResults = teachers;
    },
    sendChoice() {
      if (this.selected) {
        this.$emit('select', this.selected);
      }
    },
    fullName(user) {
      return `${user['first-name']} ${user['last-name']}`;
    },
  },
  watch: {
    query() {
      if (this.query !== '') {
        this.debouncedSearch();
      }
    },
  },
};
</script>

<style lang="scss" scoped>
  .user-search {
    display: flex;
    flex-direction: column;
    align-items: flex-start;

    > .input {
      @include d-flex-centered(flex-start);
      margin: 10px 5px;

      > img {
        width: 12px;
        height: 12px;

        &:hover {
          cursor: pointer;
        }
      }

      > input {
        font-family: $defaultFont;
        font-size: 1rem;
        margin: 0 10px;
        border-radius: 5px;
        border: inherit;
        border: solid 1px rgba($flatBlack, .5);
        padding: 5px 10px;
      }

      > .results {
        margin: 0 10px;
        padding: 5px 10px;
        border-radius: 5px;
        border: solid 1px rgba($flatBlack, .5);

        &.selected {
          background-color: rgba($flatGreen, .7);
          color: #fff;
        }
      }
    }

    > .validate {
      background-color: rgba($flatBlack, .2);
      border-radius: 5px;
      padding: 5px 10px;
      color: #fff;

      &.clickable {
        background-color: rgba($flatGreen, .9);
        &:hover {
          cursor: pointer;
        }
      }
    }
  }
</style>
