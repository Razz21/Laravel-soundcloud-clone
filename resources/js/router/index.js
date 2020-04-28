import Vue from "vue";
import Router from "vue-router";
import Layout from "@/layouts/Layout.vue";
import Home from "@/pages/Home.vue";
const NotFound404 = () =>
  import(/* webpackChunkName: "NotFound404" */ "@/pages/404.vue");
const Upload = () =>
  import(/* webpackChunkName: "upload" */ "@/pages/Upload.vue");
const FileShow = () =>
  import(/* webpackChunkName: "fileshow" */ "@/pages/FileShow.vue");
const Profile = () =>
  import(/* webpackChunkName: "profile" */ "@/pages/Profile.vue");
const Settings = () =>
  import(/* webpackChunkName: "settings" */ "@/pages/Settings.vue");
const ProfileTracks = () =>
  import(/* webpackChunkName: "profile" */ "@/pages/Profile/Tracks.vue");
import SettingsProfile from "@/pages/Settings/Profile.vue";

Vue.use(Router);

const router = new Router({
  mode: "history",
  routes: [
    {
      path: "/",
      component: Layout,
      props: true,
      children: [
        {
          path: "",
          name: "home",
          component: Home
        },

        {
          path: "upload",
          name: "tracks.upload",
          component: Upload
        },

        {
          path: "settings",
          component: Settings,
          children: [
            {
              path: "profile",
              name: "settings.profile",
              component: SettingsProfile
            }
          ]
        },
        {
          path: "404",
          name: "404",
          component: NotFound404
        },
        {
          path: ":user",
          name: "profiles.show",
          redirect: { name: "profile.tracks" },
          component: Profile,
          children: [
            {
              path: "tracks",
              name: "profile.tracks",
              component: ProfileTracks
            }
          ]
        },
        {
          path: ":user/:track",
          name: "tracks.show",
          component: FileShow
        }
      ]
    },
    { path: "*", redirect: { name: "404" } }
  ]
});
export default router;

// todo create global guards
