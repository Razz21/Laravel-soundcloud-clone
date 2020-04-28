// original code https://github.com/davland7/rplayer
import Hls from "hls.js";
import Vue from "vue";
import { debounce } from "./utils";

const setVolume = function(value) {
  localStorage.setItem("r-player-volume", value);
};
const debouncedSetLocalStorageVolume = debounce(setVolume, 1000);

class rPlayer {
  constructor() {
    if (rPlayer.instance instanceof rPlayer) {
      return rPlayer.instance;
    }

    this.state = Vue.observable({
      pause: false
    });

    this._audio = new Audio();
    this._hls = null;
    this._pause = false;
    this._duration = 0;
    this.init();
    rPlayer.instance = this;
  }

  init() {
    let initVolume = 70;
    if (localStorage.hasOwnProperty("r-player-volume")) {
      initVolume =
        parseFloat(localStorage.getItem("r-player-volume")) || initVolume;
    }
    this._audio.volume = initVolume / 100;
  }
  toggle() {
    this._pause = !this._pause;
  }

  load(src) {
    if (this.playing) this.stop();
    this._pause = true;
    if (Hls.isSupported() && src.indexOf(".m3u8") > 0) {
      this._hls = new Hls();
      this._hls.loadSource(src);
      this._hls.attachMedia(this._audio);
      this._hls.on(Hls.Events.LEVEL_LOADED, (event, data) => {
        // download only metadata
        // this._hls.stopLoad();
        this._duration = data.details.totalduration;
        // console.log("LEVEL_LOADED", data);
        // this._hls.startLoad();
      });
      this._hls.on(Hls.Events.MANIFEST_PARSED, () => {});
      // this._audio.addEventListener("play", () => {
      // start load only on manual play toggle
      // this._hls.startLoad();
      // });
    } else if (this._audio.canPlayType("application/vnd.apple.mpegurl")) {
      this._audio.src = src;
      this._audio.addEventListener("loadedmetadata", () => {
        this.play();
      });
    } else {
      this._audio.src = src;
      this._audio.load();
      this.play();
    }
  }

  play() {
    this._audio.play();
    this._pause = false;
  }

  pause() {
    if (!this._audio.paused) {
      this._audio.pause();
      this._pause = true;
    }
  }

  stop() {
    this._audio.pause();
    this._audio.currentTime = 0;
    this._audio.src = "";

    if (this._hls) {
      this._hls.destroy();
      this._hls = null;
    }
  }

  mute() {
    this._audio.muted = !this._audio.muted;
  }

  get isHls() {
    if (this._hls) return true;

    return false;
  }

  get audio() {
    return this._audio;
  }

  get playing() {
    return (
      this._audio.currentTime > 0 &&
      !this._audio.paused &&
      !this._audio.ended &&
      this._audio.ready > 2
    );
  }

  get src() {
    if (!this._audio.currentTime) {
      return null;
    } else if (this._hls) {
      return this._hls.url;
    } else {
      return this._audio.src;
    }
  }

  get muted() {
    return this._audio.muted;
  }

  get paused() {
    return this._audio.paused;
  }

  get duration() {
    return this._duration;
  }

  get volume() {
    return this._audio.volume;
  }

  set volume(value) {
    if (value >= 0 && value <= 100) {
      this._audio.volume = value / 100;
      debouncedSetLocalStorageVolume.call(null, value);
    }
  }

  get currentTime() {
    return this._audio.currentTime;
  }

  set currentTime(value) {
    this._audio.currentTime = value;
  }

  set onTimeUpdate(callback) {
    return (this._audio.ontimeupdate = callback);
  }
}

// const instance = new rPlayer();
// Object.freeze(instance);
export default rPlayer;
