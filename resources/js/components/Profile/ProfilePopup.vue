<template>
  <popup>
    <template #trigger>
      <router-link
        :to="{ name: 'profiles.show', params: { user: user.url } }"
        v-slot="{ href, navigate }"
      >
        <a class="is-link has-text-link" :href="href" @click="navigate">
          <slot></slot>
        </a>
      </router-link>
    </template>
    <div class="has-text-centered" style="padding: 0.25rem;">
      <figure class="image is-96x96">
        <img :src="user.thumbnail" class="is-rounded" />
      </figure>
      <div style="margin: 0.5rem 0;">
        <h4
          class="has-text-weight-semibold is-size-6"
          style="margin-bottom: 0.25rem;"
        >
          {{ user.screen_name }}
        </h4>
        <p class="subtitle is-7 has-text-grey is-flex justify-space-around">
          <span>
            <i class="fas fa-users"></i>
            <span class="is-inline" v-if="user.subscribers">{{
              user.subscribers.total
            }}</span>
          </span>
          <span>
            <i class="fas fa-music"></i>
            <span class="is-inline" v-if="user.tracks">{{
              user.tracks.total
            }}</span>
          </span>
        </p>
      </div>
      <button class="button is-small is-fullwidth is-info">Follow</button>
    </div>
  </popup>
</template>

<script>
// todo check if popup is preloaded on mounted; should load only on demand and only for requested element, not globally for 100s elements in list
const Popup = () => import("@/components/Popup");
export default {
  components: { Popup },
  props: {
    user: {
      type: Object,
      required: true
    }
  }
};
</script>

<style></style>
