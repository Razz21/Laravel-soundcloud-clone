import axios from "@/plugins/axios";

const execute = (method, url, data, config) => {
  return axios({
    method,
    url,
    data,
    ...config
  })
    .then(res => {
      return res.data;
    })
    .catch(err => {
      // console.trace();
      console.log("api", url, err.response);
      throw err;
    });
};

// TODO apply auth middleware(s)
// TODO add throttle/debounce to actions

export default {
  // tracks
  uploadFile(data, config) {
    return execute("POST", "/tracks", data, config);
  },
  getTrack(slug) {
    return execute("GET", `/tracks/${slug}`);
  },
  likeTrack(slug) {
    return execute("POST", `/tracks/${slug}/likes`);
  },

  getUserTrack(user, track) {
    return execute("GET", `/users/${user}/tracks/${track}`);
  },

  // profiles
  getProfile(userUrl) {
    return execute("GET", `/users/${userUrl}`);
  },
  updateProfile(data) {
    return execute("PUT", `/profile`, data);
  },
  // tags
  searchTags(data) {
    return execute("GET", `/tags?search=${data}`);
  },
  // genres
  getGenres() {
    return execute("GET", "/genres");
  },
  // comments
  getComments(track, config) {
    return execute("GET", `/tracks/${track}/comments`, null, config);
  },
  createComment(trackSlug, data) {
    return execute("POST", `/tracks/${trackSlug}/comments`, data);
  },

  subscribe(data) {
    return execute("POST", `/subscriptions/`, data);
  },
  // history
  getHistory() {
    return execute("GET", `/history`);
  },
  updateHistory(data) {
    return execute("POST", `/history`, data);
  },
  deleteFromHistory(data) {
    return execute("DELETE", `/history`, data);
  },
  getUserHistory(userId) {
    return execute("GET", `users/${userId}/history`);
  },
  // player queue
  getQueue() {
    return execute("GET", `/queue`);
  },
  updateQueue(data) {
    return execute("POST", `/queue`, data);
  }
};
