import TrackModel from "./TrackModel";
import { checkTypes } from "./utils";
const EventEmitter = require("events");

function isValidIndex(idx) {
  return /^\d+$/.test(idx);
}

class Queue {
  constructor() {
    this.elements = [];
    this.current = -1;
    this._emitter = new EventEmitter();
    this.looped = false;
  }
  on(...args) {
    return this._emitter.on(...args);
  }
  emit(...args) {
    return this._emitter.emit(...args);
  }

  add(el) {
    checkTypes(arguments, ["object"]);
    this.elements.push(new TrackModel(el));
    this.emit("update");
  }
  addArray(arr) {
    checkTypes(arguments, ["array"]);
    const els = arr.map(i => new TrackModel(i));
    this.elements = this.elements.concat(els);
    this.emit("update");
  }
  addNext(el) {
    checkTypes(arguments, ["object"]);
    this.elements.splice(this.currentIndex, 0, new TrackModel(el));
    this.emit("update");
  }

  remove(id) {
    if (!isValidIndex(id) || !id in this.elements) return false;
    if (parseInt(id) === this.current) return false;
    this.elements.splice(id, 1);
    this.emit("update");
  }
  removeByProperty(prop, value) {
    this.elements = this.elements.filter(
      item => (item.hasOwnProperty(prop) && item[prop] !== value) || item
    );
    this.emit("update");
  }
  clear() {
    this.elements = [];
    this.emit("update");
  }

  next() {
    if (!(this.currentIndex + 1 in this.elements)) {
      if (this.looped) {
        this.currentIndex = 0;
      } else {
        return false;
      }
    } else {
      this.currentIndex += 1;
    }
    return this.getCurrentEl();
  }
  prev() {
    if (!(this.currentIndex - 1 in this.elements)) {
      if (this.looped) {
        this.currentIndex = this.length - 1;
      } else {
        return false;
      }
    } else {
      this.currentIndex -= 1;
    }

    return this.getCurrentEl();
  }
  changeTo(id) {
    if (!isValidIndex(id) || !id in this.elements) return false;
    this.currentIndex = parseInt(id); //prevent SyntaxError: invalid increment/decrement operand
    return this.getCurrentEl();
  }
  getCurrentEl() {
    return this.elements[this.currentIndex];
  }

  get isEmpty() {
    return this.elements.length === 0;
  }
  get length() {
    return this.elements.length;
  }
  get currentIndex() {
    return this.current;
  }
  set currentIndex(val) {
    this.current = parseInt(val);
  }

  getState() {
    const tracks = this.elements.map(({ id }) => id);
    return { currentIndex: this.currentIndex, tracks };
  }
}

export default Queue;
