<template>
  <infinite-list
    :url="`/users/${$route.params.user}/subscribers`"
    v-model="users"
  >
    <template>
      <UserItem
        v-for="user in users"
        :key="user.id"
        :user="user"
        @follow="followUser(user.id)"
      />
    </template>
  </infinite-list>
</template>

<script>
import InfiniteList from "@/components/InfiniteList";
import UserItem from "@/components/Subscriptions/UserItem";
import { getters } from "@/store/player";
import followMixin from "@/mixins/followMixin";
export default {
  mixins: [followMixin],
  components: { InfiniteList, UserItem },
  data() {
    return {
      users: []
    };
  },
  computed: {
    current() {}
  },
  methods: {
    async followUser(id) {
      try {
        const newData = await this._followUser(this.users, id);
        console.log("followUser res", newData);
        this.users = newData;
      } catch (err) {
        console.log(err);
      }
    }
  }
};
</script>

<style></style>
