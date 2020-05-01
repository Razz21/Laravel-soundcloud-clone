<template>
  <div>
    <button @click="showuser">user</button>

    <FollowButton
      class="is-small"
      :isFollowed="followed"
      @click="followed = !followed"
    />
    <div style="width:200px;border:1px solid #000; margin:1rem"></div>
  </div>
</template>

<script>
import ContentLayout from "@/layouts/ContentLayout";
import FollowButton from "@/components/Subscriptions/FollowButton";
import { Track } from "@/components/Audio";
export default {
  components: { ContentLayout, Track, FollowButton },

  data() {
    return {
      followed: false,
      console: console
    };
  },
  methods: {
    showuser() {
      console.log("this.$auth", this.$auth);
    }
  },
  mounted() {
    // console.clear();
  },
  watch: {}
};
</script>
