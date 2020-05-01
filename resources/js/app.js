import Vue from "vue";
import router from "@/router";
import "@/vue_extensions";
import "@/plugins";
// empty .scss file to force include style-loader
// https://github.com/JeffreyWay/laravel-mix/issues/2064#issuecomment-511364566
import "../sass/style.scss";
import App from "@/App.vue";
import titleMixin from "@/mixins/titleMixin";
import authMixin from "@/mixins/authMixin";

String.prototype.getFileName = function() {
  return this.substr(0, this.lastIndexOf(".")) || this + "";
};

Vue.mixin(authMixin);

// register all components in /Global directory
const files = require.context("@/components/Global", true, /\.vue$/i);
files.keys().map(key =>
  Vue.component(
    key
      .split("/")
      .pop()
      .split(".")[0],
    files(key).default
  )
);

let app = new Vue({
  el: "#app",
  router,
  render: h => h(App)
}).$mount("#app");
