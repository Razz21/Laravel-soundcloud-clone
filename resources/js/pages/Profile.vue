<template>
  <div class="">
    <div
      class="hero is-primary absolute top-0 left-0 full-width"
      style="height: 200px;z-index:-1"
    ></div>
    <div class="is-relative inner-container" style="padding-top:70px">
      <div class="columns is-flex" v-if="profile">
        <div class="column is-narrow" style="flex: none;">
          <figure class="image is-200x200 upload-image shadow-md">
            <router-link
              v-if="$auth.can('edit.profile', profile)"
              :to="{ name: 'settings.profile' }"
              v-slot="{ href, navigate }"
            >
              <a :href="href" @click="navigate">
                <div class="overlay has-text-white">
                  Update image
                </div>
              </a>
            </router-link>
            <img :src="profile.avatar" />
          </figure>
        </div>
        <div class="column" style="margin-top: auto; padding-bottom: 2rem;">
          <div class="profile-navigation">
            <h1 class="title" style="margin-bottom: 0;">
              {{ profile.screen_name }}
            </h1>
            <div class="buttons is-right are-small">
              <div class="button">Edit profile</div>
              <div class="button is-info" v-if="$auth.can('follow', profile)">
                Follow
              </div>

              <div class="dropdown is-right" :class="{ 'is-active': dropdown }">
                <div class="dropdown-trigger">
                  <button
                    v-click-outside="() => (dropdown = false)"
                    @click="dropdown = !dropdown"
                    class="button"
                    aria-haspopup="true"
                    aria-controls="dropdown-menu"
                  >
                    <span class="icon is-small">
                      <i class="fas fa-ellipsis-h"></i>
                    </span>
                  </button>
                </div>
                <div class="dropdown-menu" id="dropdown-menu" role="menu">
                  <div class="dropdown-content">
                    <a href="#" class="dropdown-item">
                      Report
                    </a>
                    <a href="#" class="dropdown-item">
                      Block
                    </a>
                    <hr class="dropdown-divider" />
                    <a href="#" class="dropdown-item">
                      Send message
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <h2 class="subtitle" v-if="profile.subscribers">
            {{ profile.subscribers.meta.total }} Followers
          </h2>
        </div>
      </div>
      <div class="tabs">
        <ul>
          <li class="is-active"><a>Tracks</a></li>
          <li><a>History</a></li>
          <li><a>...</a></li>
        </ul>
      </div>
    </div>
    <div class="inner-container pt-8">
      <content-layout>
        <router-view></router-view>
        <template #side>
          <UserList :users="profile.subscribers" v-if="profile.subscribers" />
        </template>
      </content-layout>
    </div>
  </div>
</template>

<script>
import UserList from "@/components/Subscriptions/UserList";
import ContentLayout from "@/layouts/ContentLayout";
import LazyLoadComponent from "@/components/LazyLoadComponent";
import api from "@/api";
export default {
  components: { UserList, ContentLayout },
  data() {
    return {
      profile: null,
      dropdown: false,
      links: [
        { name: "history", text: "History" },
        { name: "favorities", text: "Favorities" },
        { name: "tracks", text: "Tracks" }
      ]
    };
  },

  extends: LazyLoadComponent(async (to, from, next, callback) => {
    try {
      const res = await api.getProfile(to.params.user);
      callback(function() {
        this.profile = res;
      });
      console.log("Profile Page", res);
    } catch (err) {
      console.log("err", err.response);
      next({ name: "404" });
    }
  }),
  methods: {
    setProfile(data) {
      this.profile = data;
    }
  },
  created() {}
};
</script>

<style lang="scss">
.profile-navigation {
  display: flex;
  justify-content: space-between;
  padding-right: 1rem;
  align-items: center;
}
</style>
