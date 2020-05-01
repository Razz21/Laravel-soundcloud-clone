import Vue from "vue";
import Hls from "hls.js";
import api from "@/api";
import { debounce } from "@/helpers/utils";

import Queue from "@/helpers/Queue";

/* constants */
const CURRENT_TRACK = "current-track";

/* helpers */
const setVolume = function(value) {
  localStorage.setItem("r-player-volume", value);
};
const debouncedSetLocalStorageVolume = debounce(setVolume, 1000);

/* config */
const queue = new Queue();
const audio = new Audio();

/* state */
const state = Vue.observable({
  currentTrack: localStorage.getItem(CURRENT_TRACK), // identify track in app
  audioPosition: 0,
  _audio: audio,
  _hls: null,
  autoPlay: false,
  duration: 0, // current track duration
  loaded: false, // current track loaded
  muted: false,
  paused: true,
  playing: false,

  volume: 70,
  mutedVal: 70,
  queue: queue,
  currentQueueUid: null // identify track in queue list with possible duplicates
});

export const getters = {
  get: prop => {
    if (state[prop] === undefined) {
      console.error(`getState: Invalid Object - "${prop}"`);
      return;
    }
    return state[prop];
  },
  currentTime: () => state._audio.currentTime,
  currentIndex: () =>
    state.queue.findIndex(({ uid }) => uid === state.currentQueueUid),
  track: () => state.queue.getCurrentEl(),
  looped: () => state.queue.looped
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
  },
  setLooped: val => (state.queue.looped = Boolean(val))
};

export const actions = {
  // player
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
    if (state._hls) {
      state._hls.destroy();
      state._hls = null;
    }
  },
  seekPosition(val) {
    mutations.setAudioPosition(val);
    state._audio.currentTime = parseInt(state.duration * val);
  },
  async changeTrack(track) {
    // add to next position and play
    queue.addNext(track);
    await actions.playNext();
  },

  async fetchTrack(id) {
    mutations.setCurrentTrack(id);
    const track = await api.getTrack(id);
    mutations.set("track", track);
    await actions.load(track.url);
  },

  // player queue
  async getQueue() {
    const response = await api.getQueue();
    actions._updateQueueFromResponse(response);
    mutations.set("loaded", true);
  },

  async syncQueueWithDB() {
    const data = queue.getState();
    const response = await api.updateQueue(data);
    // console.log(response);
  },

  addToQueue(track) {
    queue.add(track);
    console.log(queue);
  },

  removeFromQueue(idx) {
    queue.remove(idx);
  },

  async playSelected(idx) {
    const next = queue.changeTo(idx);
    await actions.loadTrack(next);
  },

  async playNext() {
    const next = queue.next();
    console.log("next", next, queue);
    await actions.loadTrack(next);
  },

  async playPrev() {
    const prev = queue.prev();
    console.log("prev", prev, queue);
    await actions.loadTrack(prev);
  },

  async loadTrack(next) {
    if (!next) {
      return;

      // actions.stop();
    } else {
      mutations.setCurrentTrack(next.id);
      mutations.set("currentQueueUid", next.uid);
      await actions.load(next.url);
      actions.play();
      actions.updateQueueOrder();
    }
  },
  updateQueueOrder() {
    const newIdx = queue.elements.findIndex(
      ({ uid }) => uid === state.currentQueueUid
    );
    if (newIdx > -1) {
      queue.current = newIdx;
    }
  },

  _updateQueueFromResponse(response) {
    // response return tracks collection with active item array index
    if (response.hasOwnProperty("tracks")) {
      queue.addArray(response.tracks);
      queue.currentIndex = parseInt(response.currentIndex) || 0;
      // let currentIndex = parseInt(response.currentIndex) || 0;
      // currentIndex = Math.min(Math.max(currentIndex, 0), queueItems.length - 1);
      const current = queue.getCurrentEl();
      const currentQueueUid = current && current.uid;
      console.log("_updateQueueFromResponse response", response);
      console.log("_updateQueueFromResponse queue", queue);
      console.log("_updateQueueFromResponse current", current, currentQueueUid);
      mutations.set("currentQueueUid", currentQueueUid);
    }
  }
};

const debounceSyncQueue = debounce(actions.syncQueueWithDB, 5000);
queue.on("update", () => {
  actions.updateQueueOrder();
  // debounceSyncQueue();
});

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
