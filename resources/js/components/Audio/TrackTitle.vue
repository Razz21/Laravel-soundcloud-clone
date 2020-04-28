<template>
  <div>
    <template v-if="track_slug">
      <router-link
        :to="{
          name: 'tracks.show',
          params: { user: user.url, track: track_slug }
        }"
        v-slot="{ href, navigate }"
      >
        <a class="is-link has-text-link" :href="href" @click="navigate">
          <h1
            class="title has-text-weight-normal"
            :class="[titleClass, { truncate: truncateTitle }]"
          >
            {{ title }}
          </h1>
        </a>
      </router-link>
    </template>
    <h1
      v-else
      class="title has-text-weight-normal"
      :class="[titleClass, { truncate: truncateTitle }]"
    >
      {{ title }}
    </h1>
    <p class="subtitle" :class="subtitleClass">
      <span>by </span>
      <!-- <profile-popup :user="user"> -->

      <router-link
        :to="{ name: 'profiles.show', params: { user: user.url } }"
        v-slot="{ href, navigate }"
      >
        <a class="is-link has-text-link" :href="href" @click="navigate">
          <span :class="{ truncate: truncateUser }">{{
            user.screen_name
          }}</span>
        </a>
      </router-link>
      <!-- </profile-popup> -->
    </p>
  </div>
</template>

<script>
import ProfilePopup from "@/components/Profile/ProfilePopup";
export default {
  components: { ProfilePopup },
  props: {
    title: { type: String, required: true },
    track_slug: { type: String },
    user: { type: Object, required: true },
    titleClass: { type: String, default: "is-6" },
    subtitleClass: { type: String, default: "is-6" },
    truncateTitle: { type: Boolean, default: false },
    truncateUser: { type: Boolean, default: false }
  }
};
</script>

<style></style>
