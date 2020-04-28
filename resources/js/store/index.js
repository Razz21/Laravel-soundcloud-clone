import Vue from "vue";
import api from "@/api";

const state = Vue.observable({
  authModal: "",
  alerts: [],
  genres: []
});

export const getters = {
  get: prop => {
    if (state[prop] === undefined) {
      console.error(`getState: Invalid Object - "${prop}"`);
      return;
    }
    return state[prop];
  },
  authModal: () => state.authModal,
  alerts: () => state.alerts,
  genres: () => state.genres
};

export const mutations = {
  set: (prop, value) => {
    if (state[prop] === undefined || value === undefined) {
      console.error(`setState: Invalid Object or Value - "${prop}"`);
      return;
    }
    state[prop] = value;
  },
  setAuthModal: val => (state.authModal = val),
  setAlert: val => state.alerts.push(val),
  setGenres: val => (state.genres = val)
};

export const actions = {
  async fetchGenres() {
    try {
      const res = await api.getGenres();
      mutations.setGenres(res);
    } catch (err) {
      console.log(err);
    }
  },
  async fetchTags(data) {
    try {
      const res = await api.searchTags(data);
      return res;
    } catch (err) {
      console.log(err);
    }
  }
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
export const mapActions = props => {
  return props.reduce((result, prop) => {
    result[prop] = function(...args) {
      return actions[prop].call(this, ...args);
    };
    return result;
  }, {});
};

export default {
  getters,
  mutations,
  actions
};
