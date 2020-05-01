<template>
  <li class="queue-item" :class="{ active: isCurrentTrack }">
    <VIcon icon="fas fa-bars" class="handle"></VIcon>
    <article class="media align-center">
      <v-img
        :src="track.get('cover')"
        class="is-32x32"
        fallback="track-img-fallback"
        @click="togglePlay"
      ></v-img>
      <div class="media-content ml-2 ">
        <div class="content">
          <h4 class="is-size-7 text truncate" :title="track.get('title')">
            {{ track.get("title") }}
          </h4>
          <h5 class="is-size-7 text">
            {{ track.user.screen_name }}
          </h5>
        </div>
      </div>
    </article>
    <button
      class="delete"
      @click="$emit('delete')"
      v-if="!isCurrentTrack"
    ></button>
  </li>
</template>

<script>
import TrackModel from "@/helpers/TrackModel";
import TrackMixin from "@/mixins/TrackMixin";

import { getters, mutations, mapActions, mapGetters } from "@/store/player";
export default {
  // mixins: [TrackMixin],
  components: {},
  props: {
    track: {
      type: TrackModel,
      required: true
    },
    isCurrentTrack: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    ...mapGetters(["paused", "duration", "currentTrack", "_audio"]),
    isPlaying() {
      return this.isCurrentTrack && !this.paused;
    }
  },
  methods: {
    ...mapActions(["play", "pause", "changeTrack"]),
    togglePlay() {
      if (this.isCurrentTrack) {
        if (this.paused) {
          this.play();
        } else {
          this.pause();
        }
      } else {
        this.$emit("changeTo");
      }
    }
  }
};
</script>

<style></style>
