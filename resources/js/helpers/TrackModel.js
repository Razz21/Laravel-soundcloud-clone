import { v4 as uuid } from "uuid";
export default class TrackModel {
  constructor(track) {
    this.track = { ...track, uid: uuid() };
    delete this.track.wave;
  }
  get id() {
    return this.track.id;
  }
  get uid() {
    return this.track.uid;
  }
  get user() {
    return this.track.user;
  }
  get url() {
    return this.track.url;
  }
  get(prop = null) {
    if (prop) {
      return this.track[prop];
    }
    return this.track;
  }
}
