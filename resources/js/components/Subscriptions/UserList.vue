<template>
  <div class="user-list">
    <div class="is-flex justify-space-between align-flex-end">
      <div class="title is-5">
        Followers
      </div>
      <button class="button is-small">
        <span>
          {{ users.meta.total }}
        </span>
        <span class="icon is-small">
          <i class="fas fa-angle-right"></i>
        </span>
      </button>
    </div>
    <hr />
    <UserItem
      v-for="user in users.data"
      :key="user.id"
      :user="user"
      @follow="followUser"
    />
  </div>
</template>

<script>
// TODO handle follow button
import UserItem from "./UserItem";
export default {
  components: { UserItem },
  props: {
    users: {
      type: Object,
      required: true
    }
  },
  methods: {
    followUser(id) {
      return;
    }
  }
};
</script>

<style lang="scss">
.user-list {
  .title {
    margin: 0;
  }
  hr {
    margin: 0.5rem 0;
  }
}
</style>
