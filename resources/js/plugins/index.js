import Vue from "vue";
import Echo from "laravel-echo";
import * as uuid from "uuid";
import pusher from "pusher-js";
import axios from "./axios";
import { register } from "./VueAuth";
import VueAuth from "./VueAuth";
import "./VeeValidate";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import VueTooltip from "vue-directive-tooltip";
import moment from "moment";
import "vue-directive-tooltip/dist/vueDirectiveTooltip.css";
import InfiniteLoading from "vue-infinite-loading";

Vue.use(VueTooltip, {
  class: "vue-tooltip__info",
  triggers: ["hover"]
});

register("follow", (user, target) => {
  // true only, if target is not current user, or is anonymuous to not break layout
  if (user) {
    return user.id !== target.id;
  }
  return true;
});
register("like", (user, target) => {
  // return true only for authenticated user, if is not owner
  return user && user.id !== target.user_id;
});

register("edit.profile", (user, target) => {
  // return true only for authenticated user, if is owner
  return user && user.id === target.id;
});

Vue.use(VueAuth, { userPath: "/user", axios });

Vue.use(InfiniteLoading);

Vue.prototype.$http = axios;
Vue.prototype.$uuid = uuid;

// moment.locale("en", {
//   relativeTime: {
//     future: "in %s",
//     past: "%s ago",
//     s: "seconds",
//     ss: "%ss",
//     m: "a minute",
//     mm: "%dm",
//     h: "an hour",
//     hh: "%dh",
//     d: "a day",
//     dd: "%dd",
//     M: "a month",
//     MM: "%dM",
//     y: "a year",
//     yy: "%dY"
//   }
// });

Vue.prototype.$moment = moment;

Vue.prototype.$echo = new Echo({
  broadcaster: "pusher",
  key: process.env.MIX_PUSHER_APP_KEY,
  cluster: process.env.MIX_PUSHER_APP_CLUSTER,
  forceTLS: true,
  encrypted: true
  // disableStats: true,
  // enabledTransports: ["ws", "wss"]
});

Vue.component("ValidationObserver", ValidationObserver);
Vue.component("ValidationProvider", ValidationProvider);
