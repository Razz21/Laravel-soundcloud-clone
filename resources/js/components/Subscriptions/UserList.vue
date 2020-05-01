<template>
  <side-content>
    <template #title>Followers</template>
    <template #right>
      {{ users.meta.total }}
    </template>
    <UserItem
      v-for="user in users.data"
      :key="user.id"
      :user="user"
      @follow="followUser"
    />
  </side-content>
</template>

<script>
// TODO handle follow button
import UserItem from "./UserItem";
import SideContent from "./SideContent";
import api from "@/api";
export default {
  components: { UserItem, SideContent },
  props: {
    users: {
      type: Object,
      required: true
    }
  },
  methods: {
    async followUser(id) {
      try {
        const res = await this._auth(() => api.subscribe({ user_id: id }));
        const data = this.users.data.map(u => {
          if (u.id === res.id) {
            return res;
          }
          return u;
        });

        this.$emit("update:users", { ...this.users, data });
      } catch (err) {}
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
