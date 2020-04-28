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

let app = new Vue({
  el: "#app",
  router,
  render: h => h(App)
}).$mount("#app");
