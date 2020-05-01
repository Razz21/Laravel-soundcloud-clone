<template>
  <div class="columns">
    <div class="column is-flex flex-column">
      <div style="flex:1" class="full-width align-center is-flex">
        <div class="is-flex track__meta full-width">
          <PlayButton
            :isPlaying="isPlaying"
            class="is-size-3"
            style="margin-right:2rem"
            @click="togglePlay"
          />

          <div class="track__title">
            <TrackTitle
              :user="track.user"
              :title="track.title"
              titleClass="is-3 has-text-white-ter"
              subtitleClass="is-4 has-text-white-ter"
            />
          </div>
          <div class="track__tags" v-if="track.genre">
            <div class="tags are-medium">
              <a class="tag"> #{{ track.genre.name }} </a>
            </div>
          </div>
        </div>
      </div>

      <div style="height:100px" class="is-fullwidth is-relative">
        <div
          class="has-background-grey-dark has-text-grey-lighter is-size-7 track-time"
        >
          {{ _audio.currentTime | remainingTime(duration) }}
        </div>
        <Waveform
          :waveData="track.wave"
          :progress="progress"
          @onClick="changeAudioPosition"
        />
      </div>
    </div>
    <div class="column is-narrow ">
      <v-img
        :src="track.cover"
        class="is-300x300"
        fallback="has-background-grey-light"
      ></v-img>
    </div>
  </div>
</template>

<script>
import Waveform from "./Audio/Waveform";
import PlayButton from "./Audio/PlayButton";
import TrackTitle from "./Audio/TrackTitle";
import rPlayer from "@/helpers/rPlayer";
import { getters, mutations, mapActions, mapGetters } from "@/store/player";
import TrackMixin from "@/mixins/TrackMixin";
export default {
  mixins: [TrackMixin],
  components: { Waveform, TrackTitle, PlayButton },
  props: {
    track: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      // player: new rPlayer(),
      console: console
    };
  },
  computed: {
    isCurrentTrack() {
      return parseInt(this.currentTrack) === this.track.id;
    }
  },

  methods: {
    onPreview(val) {
      // todo handle preview value
    }
  }
};
</script>

<style>
.track-time {
  position: absolute;
  right: 0;
  bottom: 1.25rem;
  padding: 0.25rem;
  z-index: 5;
}
</style>
