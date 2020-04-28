<template>
  <div class="media user-item">
    <figure class="media-left">
      <p class="image is-48x48">
        <img :src="user.thumbnail" />
      </p>
    </figure>
    <div class="media-content">
      <div class="user-item__content">
        <strong class="title is-7 truncate">{{ user.screen_name }}</strong>
        <div class="is-size-7 has-text-link">
          {{ user.subscribers.total }} Subscribers
        </div>
        <!-- <div class="is-size-7 truncate user-location">
          {{ [user.profile.city, user.profile.country].join(", ") }}
        </div> -->
      </div>
      <div class="actions">
        <FollowButton
          class="is-small"
          :isFollowed="user.is_subscribed"
          @click="$emit('follow', user.id)"
        />
      </div>
    </div>
  </div>
</template>

<script>
import FollowButton from "./FollowButton";
export default {
  components: { FollowButton },
  props: {
    user: {
      type: Object,
      required: true
    }
  },
  computed: {
    location() {
      return;
    }
  }
};
</script>

<style lang="scss">
.user-item {
  padding: 0.5rem !important;
  & + .user-item {
    margin-top: 0;
  }
  padding: 0.5rem;
  max-width: 100%;
  figure.media-left {
    margin: 0;
  }

  .image {
    border-radius: $radius;
    overflow: hidden;
  }
  .media-content {
    flex: 1;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    min-width: 0;
    > .user-item__content {
      min-width: 0;
      padding: 0 1rem;
    }
    .actions {
    }
  }
}
</style>
