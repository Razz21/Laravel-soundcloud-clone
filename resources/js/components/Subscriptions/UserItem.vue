<template>
  <div class="media user-item">
    <figure class="media-left">
      <p class="image is-48x48">
        <img :src="user.thumbnail" />
      </p>
    </figure>
    <div class="media-content">
      <div class="user-item__content">
        <router-link
          :to="{ name: 'profiles.show', params: { user: user.url } }"
          v-slot="{ href, navigate }"
        >
          <a class="is-link" :href="href" @click="navigate">
            <strong class="is-size-7 has-text-weight-semibold truncate">
              {{ user.screen_name }}
            </strong>
          </a>
        </router-link>
        <div class="is-size-7 has-text-link">
          {{ user.subscribers.meta.total }}{{ "&nbsp;" }}
          {{ "Follower" | pluralize(user.subscribers.meta.total) }}
        </div>
        <div
          class="is-size-7 truncate user-location has-text-grey"
          style="min-width:0"
          v-if="location"
          :title="location"
        >
          {{ location }}
        </div>
      </div>
      <div class="actions">
        <FollowButton
          v-if="$auth.can('follow', user)"
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
      return [this.user.profile.city, this.user.profile.country].join(", ");
    }
  }
};
</script>

<style lang="scss">
.user-item {
  align-items: center !important;
  padding: 0.25rem !important;
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
    align-items: center;
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
