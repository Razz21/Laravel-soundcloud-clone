<template>
  <infinite-list class="mt-8" :url="apiEndpoint" v-model="tracks">
    <Track
      v-for="track in tracks"
      :key="track.id"
      :track="track"
      :isCurrentTrack="checkCurrentTrack(track.id)"
    />
  </infinite-list>
</template>

<script>
import InfiniteList from "@/components/InfiniteList";
import Track from "@/components/Audio/Track";
import { getters } from "@/store/player";
export default {
  props: {
    endpoint: {
      type: String,
      required: true
    }
  },
  components: { InfiniteList, Track },
  data() {
    return {
      tracks: []
    };
  },
  computed: {
    current() {
      return getters.get("currentTrack");
    },
    apiEndpoint() {
      return `/users/${this.$route.params.user}/${this.endpoint}`;
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
