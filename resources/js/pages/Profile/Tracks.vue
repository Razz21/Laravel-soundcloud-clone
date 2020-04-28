<template>
  <infinite-list class="mt-8" :url="`/api/users/${$route.params.user}/tracks`">
    <template v-slot="{ items: { items: tracks } }">
      <Track
        v-for="track in tracks"
        :key="track.id"
        :track="track"
        :isCurrentTrack="checkCurrentTrack(track.id)"
      />
    </template>
  </infinite-list>
</template>

<script>
import InfiniteList from "@/components/InfiniteList";
import { Track } from "@/components/Audio";
import { getters } from "@/store/player";
export default {
  components: { InfiniteList, Track },
  computed: {
    current() {
      return getters.get("currentTrack");
    }
  },
  methods: {
    checkCurrentTrack(id) {
      return this.current === id;
    }
  }
};
</script>

<style></style>
