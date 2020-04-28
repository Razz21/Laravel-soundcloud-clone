<template>
  <div
    class="audio-panel has-background-dark has-text-white-bis"
    :class="{ show: loaded }"
  >
    <div class="container is-fullhd ">
      <div class="container is-fluid is-flex pl-3 pr-3 pt-2 pb-2 align-center">
        <div class="buttons">
          <VButton icon="fas fa-step-backward" class="is-dark" />
          <VButton
            icon="fas fa-play"
            v-if="paused"
            class="is-dark"
            @click="play"
          />
          <VButton icon="fas fa-pause" v-else class="is-dark" @click="pause" />
          <VButton icon="fas fa-step-forward" class="is-dark" />
        </div>
        <div class="time">
          {{ _audio.currentTime | elapsedTime }}
        </div>
        <Timeline :audioPosition="audioPosition" @seek="seekPosition" />
        <div class="time">
          -{{ _audio.currentTime | remainingTime(duration) }}
        </div>
        <Volume :volume.sync="volume" @mute="mute" />
        <!-- {{ timelineTime }} -->
      </div>
    </div>
  </div>
</template>

<script>
import VButton from "@/components/UI/General/VButton";
import PlayButton from "./PlayButton";
import {
  mapActions,
  getters,
  mutations,
  mapPropsGetterSetters,
  mapGetters,
  actions
} from "@/store/player";
import Volume from "./Volume";
import Timeline from "./Timeline";
import Hls from "hls.js";
import { debounce } from "@/helpers/utils";
// change track
// update track position
export default {
  components: { VButton, PlayButton, Volume, Timeline },
  data() {
    return {
      looped: false,
      console,
      mutedVal: this.volume
    };
  },

  computed: {
    ...mapPropsGetterSetters([
      "duration",
      "paused",
      "autoPlay",
      "playing",
      "volume",
      "muted"
    ]),
    ...mapGetters(["currentTrack", "_audio", "loaded", "track"]),

    volume: {
      get() {
        return getters.get("volume");
      },
      set(value) {
        mutations.setVolume(value);
      }
    },
    audioPosition: {
      get() {
        return getters.get("audioPosition");
      },
      set(val) {
        mutations.setAudioPosition(val);
      }
    },
    currentTime: {
      get() {
        return getters.currentTime();
      },
      set(val) {
        mutations.setCurrentTime(val);
      }
    }
  },
  methods: {
    ...mapActions(["load", "seekPosition", "pause", "play", "fetchTrack"]),
    mute() {
      this.muted = !this.muted;
      if (this.muted) {
        this.mutedVal = this.volume;
        this.volume = 0;
        this.muted = true;
      } else {
        this.volume = this.mutedVal;
      }
    },
    _handleLoaded() {
      if (this._audio.readyState >= 2) {
      } else {
        throw new Error("Failed to load sound file");
      }
    },
    _progressAnimation() {
      this.audioPosition = this._audio.currentTime / this._audio.duration;
      requestAnimationFrame(this._progressAnimation);
    },
    _handlePlay(e) {
      requestAnimationFrame(this._progressAnimation);
    },
    togglePlay() {
      if (this.paused) {
        this.play();
      } else {
        this.pause();
      }
    },

    init() {
      this._audio.addEventListener("loadeddata", this._handleLoaded);
      this._audio.addEventListener("play", this._handlePlay);
      ["abort", "ended", "pause"].forEach(event => {
        this._audio.addEventListener(event, this.pause, false);
      });
    },
    initVolume() {
      let initVolume = 70;
      if (localStorage.hasOwnProperty("r-player-volume")) {
        initVolume =
          parseFloat(localStorage.getItem("r-player-volume")) || initVolume;
      }
      this._audio.volume = initVolume / 100;
      this.volume = initVolume;
    },
    _handleKeyControl(e) {
      const key = e.keyCode || e.which;
      if (key === 32 && e.target == document.body) {
        e.preventDefault();
        e.stopPropagation();
        debounce(this.togglePlay());
      }
    }
  },

  mounted() {
    this.initVolume();
    this.init();
    window.addEventListener("keydown", this._handleKeyControl, false);
  },
  beforeDestroy() {
    this._audio.removeEventListener("loadeddata", this._handleLoaded);
    this._audio.removeEventListener("play", this._handlePlay);
    ["abort", "ended", "pause"].forEach(event => {
      this._audio.removeEventListener(event, this.pause, false);
    });
    window.removeEventListener("keydown", this._handleKeyControl);
  },
  watch: {
    // TODO meta title
    // playing(val) {
    //   if (val) {
    //     document.title = this.track.title;
    //   }
    // },
  },
  created() {
    if (this.currentTrack) {
      this.fetchTrack(this.currentTrack);
    }
  }
};
</script>

<style lang="scss">
.audio-panel {
  *,
  *::after,
  *::before {
    -webkit-user-select: none;
    -webkit-user-drag: none;
    -webkit-app-region: no-drag;
    user-select: none;
  }

  border-top: 4px solid #555;
  position: fixed;
  bottom: 0;
  left: 0;
  z-index: 1000;
  width: 100%;
  min-width: $desktop;
  --headsize: 8px;
  transform: translateY(100%);
  transition: transform 0.5s;
  &.show {
    transform: translateY(0);
  }
  .line {
    position: relative;
    background-color: #444;
    transition: all 0.5s ease;
    &::after {
      content: "";
      position: absolute;
      width: var(--headsize);
      height: var(--headsize);
      border-radius: 50%;
      background-color: rgb(255, 107, 151);
    }
    &::before {
      content: "";
      left: 0;
      bottom: 0;
      position: absolute;
      background-color: rgb(255, 107, 151);
    }
  }
  .buttons {
    margin-bottom: 0 !important;
  }
  .button {
    margin-bottom: 0 !important;
    & *,
    &:active,
    &:focus {
      outline: 0;
    }
    &::-moz-focus-inner {
      border: 0;
    }
  }
  .time {
    margin: 0 0.5rem;
    font-size: 0.9rem;
  }
  .timeline-wrap {
    flex: 1;

    padding: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
  }
  .timeline {
    width: calc(100% - 4px);
    height: 2px;
    &::before {
      content: "";
      height: 100%;
      width: calc(var(--timeline) * 1%);
    }
    &::after {
      content: "";
      top: 50%;
      transform: translate(-50%, -50%);
      left: calc(var(--timeline) * 1%);
    }
  }
  .volume {
    overflow: visible;
    cursor: pointer;
    position: relative;

    &:hover .volume-panel,
    &:active .volume-panel {
      opacity: 1;
      transform: translateY(-100%);
      transition-duration: 0.2s;
      transition-delay: 0s, 0.1s;
    }
    .volume-panel {
      border-radius: $radius;
      border: 1px solid #555;
      z-index: -1;
      transform-origin: center bottom;
      transition-property: transform, opacity;
      transition-duration: 0.2s;
      transition-delay: 0.2s, 0.1s;

      transition-timing-function: ease-out;
      padding: 0.8rem 0.4rem;
      opacity: 0;
      display: flex;
      transform: translateY(0);
      position: absolute;
      left: 0;
      height: 100px;
      width: 100%;
      justify-content: center;
      background-color: inherit;
      .volumeline {
        width: 2px;
        &::before {
          content: "";
          width: 100%;
          height: calc(var(--volume) * 1%);
        }
        &::after {
          content: "";
          left: 50%;
          transform: translate(-50%, 50%);
          bottom: calc(var(--volume) * 1%);
        }
      }
    }
  }
}
</style>
