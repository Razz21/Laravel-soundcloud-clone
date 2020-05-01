"use strict";
/*
Vue plugin to manage basic authentication with Laravel Arilock/Sanctum using cookies
*/
import { checkTypes } from "@/helpers/utils";
import { mutations } from "@/store";
const defaults = {
  name: "$auth",
  loginPath: "/login",
  logoutPath: "/logout",
  registerPath: "/register",
  userPath: "/user",
  cookiePath: "/sanctum/csrf-cookie",
  axios: null
};

const permissions = {
  perms: {},
  get(name) {
    if (this.perms[name]) {
      return this.perms[name];
    } else {
      console.error(
        `"${name}" permission does not exists. Did you register it correctly?`
      );
      return this.error;
    }
  },
  register(name, cb) {
    this.perms[name] = cb;
  },
  error() {
    return false;
  }
};

export function register(name, callback) {
  checkTypes(arguments, ["string", "function"]);
  permissions.register(name, callback);
}

const properties = function(Vue, o) {
  return (function() {
    const state = Vue.observable({
      _user: null
    });

    return {
      login(data) {
        return o.axios
          .post(o.loginPath, data)
          .then(res => {
            return res;
          })
          .catch(err => {
            throw err;
          });
      },
      register(data) {
        return o.axios
          .post(o.registerPath, data)
          .then(res => {
            return res;
          })
          .catch(err => {
            throw err;
          });
      },
      getCookie() {
        return o.axios.get(o.cookiePath);
      },
      logout() {
        return o.axios.post(o.logoutPath).finally(() => {
          state._user = null;
        });
      },
      getUser() {
        return o.axios
          .get(o.userPath)
          .then(res => {
            state._user = res.data;
            return res;
          })
          .catch(err => {
            throw err;
          });
      },
      can(name, ...args) {
        checkTypes(arguments, ["string"]);
        // if (!this.isLogged) return false;
        const callback = permissions.get(name);
        return callback(this.user, ...args);
      },
      cannot(name, ...args) {
        return !this.can(name, ...args);
      },
      modal(type = "login") {
        if (!this.isLogged) {
          mutations.setAuthModal(type);
        }
      },
      get user() {
        return state._user;
      },
      get isLogged() {
        return !!this.user;
      }
    };
  })();
};

export default {
  install: function(Vue, opts = {}) {
    const options = Object.assign(defaults, opts);
    const axios = options.axios;
    if (!axios || typeof axios !== "function") {
      throw new Error("VueAuth require axios instance!");
    }
    Vue.prototype[options.name] = Object.freeze(properties(Vue, options));
  }
};
