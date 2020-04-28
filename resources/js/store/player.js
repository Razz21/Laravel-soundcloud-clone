import Vue from "vue";
import Hls from "hls.js";
import api from "@/api";
import { debounce } from "@/helpers/utils";

const CURRENT_TRACK = "current-track";
const setVolume = function(value) {
  localStorage.setItem("r-player-volume", value);
};
const debouncedSetLocalStorageVolume = debounce(setVolume, 1000);

const state = Vue.observable({
  currentTrack: localStorage.getItem(CURRENT_TRACK), //id to fetch data by player
  audioPosition: 0,
  _audio: new Audio(),
  _hls: null,
  autoPlay: false,
  duration: 0,
  loaded: false,
  looped: false,
  muted: false,
  paused: true,
  playing: false,
  track: null,
  volume: 70,
  console,
  mutedVal: 70
});

export const getters = {
  get: prop => {
    if (state[prop] === undefined) {
      console.error(`getState: Invalid Object - "${prop}"`);
      return;
    }
    return state[prop];
  },
  currentTime: () => state._audio.currentTime
};

export const mutations = {
  set: (prop, value) => {
    if (state[prop] === undefined || value === undefined) {
      console.error(`setState: Invalid Object or Value - "${prop}"`);
      return;
    }
    state[prop] = value;
  },
  setCurrentTrack: val => {
    state.currentTrack = parseInt(val);
    localStorage.setItem(CURRENT_TRACK, val);
  },
  setAudioPosition: val => (state.audioPosition = parseFloat(val).toFixed(4)),
  setVolume: value => {
    if (value >= 0 && value <= 100) {
      state.volume = value;
      state._audio.volume = value / 100;
      debouncedSetLocalStorageVolume.call(null, value);
    }
  },
  setCurrentTime: val => {
    state._audio.currentTime = parseInt(val);
  }
};

export const actions = {
  load(src) {
    return new Promise(resolve => {
      if (state.playing) actions.stop();
      if (Hls.isSupported() && src.indexOf(".m3u8") > 0) {
        state._hls = new Hls();
        state._hls.loadSource(src);
        state._hls.attachMedia(state._audio);

        state._hls.on(Hls.Events.LEVEL_LOADED, (event, data) => {
          mutations.set("duration", data.details.totalduration);
          // state._hls.stopLoad();
          mutations.set("loaded", true);
        });
        state._hls.on(Hls.Events.MANIFEST_PARSED, () => {
          resolve();
        });
        // state._audio.addEventListener("play", () => {
        //   // start load only on manual play toggle
        //   state._hls.startLoad();
        // });
      } else if (state._audio.canPlayType("application/vnd.apple.mpegurl")) {
        state._audio.src = src;
        state._audio.addEventListener("loadedmetadata", () => {
          actions.play();
          resolve();
        });
      } else {
        state._audio.src = src;
        state._audio.load();
        actions.play();
        resolve();
      }
    });
  },
  play() {
    if (state.playing && !state.paused) return;
    mutations.set("paused", false);
    state._audio.play();
    mutations.set("playing", true);
  },
  pause() {
    if (!state.paused) {
      state._audio.pause();
      mutations.set("paused", true);
      mutations.set("playing", false);
    }
  },
  stop() {
    mutations.set("playing", false);
    mutations.set("paused", true);
    state._audio.pause();
    state._audio.currentTime = 0;
    state._audio.src = "";
    // this.currentTime = 0;
    if (state._hls) {
      state._hls.destroy();
      state._hls = null;
    }
  },
  seekPosition(val) {
    mutations.setAudioPosition(val);
    state._audio.currentTime = parseInt(state.duration * val);
  },
  async changeTrack(id) {
    await actions.fetchTrack(id);
    actions.play();
  },
  async fetchTrack(id) {
    mutations.setCurrentTrack(id);
    const track = await api.getTrack(id);
    mutations.set("track", track);
    await actions.load(track.url);
  }
};

export default {
  getters,
  mutations,
  actions
};

export const mapPropsGetterSetters = props => {
  return props.reduce((result, prop) => {
    result[prop] = {
      get() {
        return getters.get(prop);
      },
      set(val) {
        mutations.set(prop, val);
      }
    };
    return result;
  }, {});
};
export const mapGetters = props => {
  return props.reduce((result, prop) => {
    result[prop] = function() {
      return getters.get(prop);
    };
    return result;
  }, {});
};
export const mapMutations = props => {
  return props.reduce((result, prop) => {
    result[prop] = function(...args) {
      return mutations.set(...args);
    };
    return result;
  }, {});
};
export const mapActions = function(props) {
  return props.reduce((result, prop) => {
    result[prop] = actions[prop];
    return result;
  }, {});
};
