import axios from "@/plugins/axios";
function getFunctionCallerName() {
  // gets the text between whitespace for second part of stacktrace
  return new Error().stack.match(/at (\S+)/g)[1].slice(3);
}

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
// TODO add throttle/debounce actions

export default {
  // tracks
  uploadFile(data, config) {
    return execute("POST", "/api/tracks", data, config);
  },
  getTrack(id) {
    return execute("GET", `/api/tracks/${id}`);
  },
  likeTrack(id) {
    return execute("POST", `/api/tracks/${id}/likes`);
  },

  getUserTrack(user, track) {
    return execute("GET", `/api/users/${user}/tracks/${track}`);
  },

  // profiles
  getProfile(url) {
    console.log("getProfile", url);
    return execute("GET", `/api/users/${url}`);
  },
  updateProfile(data) {
    return execute("PUT", `/api/profile`, data);
  },
  // tags
  searchTags(data) {
    return execute("GET", `/api/tags?search=${data}`);
  },
  // genres
  getGenres() {
    return execute("GET", "/api/genres");
  },
  // comments
  getComments(track, config) {
    return execute("GET", `/api/tracks/${track}/comments`, null, config);
  },
  createComment(track, data) {
    return execute("POST", `/api/tracks/${track}/comments`, data);
  },

  subscribe(data) {
    return execute("POST", `/api/subscriptions/`, data);
  }
};
