import { getters, mutations, mapActions, mapGetters } from "@/store/player";
export default {
  computed: {
    ...mapGetters(["paused", "duration", "currentTrack", "_audio"]),

    isPlaying() {
      return this.isCurrentTrack && !this.paused;
    },
    currentTime() {
      return getters.currentTime();
    },
    audioPosition: {
      get() {
        return this.isCurrentTrack ? getters.get("audioPosition") : 0;
      },
      set(val) {
        mutations.setAudioPosition(val);
      }
    },
    progress() {
      return this.isCurrentTrack ? parseFloat(this.audioPosition * 100) : 0;
    }
  },
  methods: {
    ...mapActions(["seekPosition", "play", "pause", "changeTrack"]),
    changeAudioPosition(val) {
      if (this.isCurrentTrack) {
        this.seekPosition(val);
      } else {
        this.changeTrack(this.track);
      }
    },
    togglePlay() {
      if (this.isCurrentTrack) {
        if (this.paused) {
          this.play();
        } else {
          this.pause();
        }
      } else {
        this.changeTrack(this.track);
      }
    }
  }
};
