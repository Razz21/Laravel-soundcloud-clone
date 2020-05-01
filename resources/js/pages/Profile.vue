<template>
  <div class="">
    <div
      class="hero is-primary absolute top-0 left-0 full-width"
      style="height: 200px;z-index:-1"
    ></div>
    <div class="is-relative inner-container" style="padding-top:85px">
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
        <div class="column pb-8" style="margin-top: auto; ">
          <div class="profile-navigation">
            <h1 class="title mb-0 select-none">
              {{ profile.screen_name }}
            </h1>
            <div class="buttons is-right ">
              <button class="button" v-if="$auth.can('edit.profile', profile)">
                Edit profile
              </button>

              <FollowButton
                v-if="$auth.can('follow', profile)"
                :isFollowed="profile.is_subscribed"
                @click="FollowProfile"
              />

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
          <h2 class="subtitle select-none">
            {{ profile.subscribers.meta.total }} Followers
          </h2>
        </div>
      </div>
      <div class="tabs">
        <ul>
          <router-link
            v-for="link in links"
            :key="link.text"
            :to="link.path"
            v-slot="{ href, navigate, isExactActive }"
          >
            <li :class="{ 'is-active': isExactActive }">
              <a :href="href" @click="navigate">
                {{ link.text }}
              </a>
            </li>
          </router-link>
        </ul>
      </div>
    </div>
    <div class="inner-container pt-8">
      <content-layout>
        <keep-alive>
          <router-view :key="$route.path"></router-view>
        </keep-alive>
        <template #side>
          <side-content v-if="location">
            <template #title>Location</template>
            <div class="is-flex">
              <VIcon icon="fas fa-map-marker-alt" />
              <div class="subtitle ml-4">{{ location }}</div>
            </div>
          </side-content>
          <side-content v-if="profile.profile.description">
            <template #title>Description</template>

            <more-content height="200px">
              <div class="is-size-7" style="white-space:pre-line">
                {{ profile.profile.description }}
              </div>
            </more-content>
          </side-content>
          <side-content
            :to="{ name: 'profile.following', params: { user: profile.url } }"
          >
            <template #title>Followers</template>
            <template #right>
              {{ profile.subscribers.meta.total }}
            </template>
            <UserItem
              v-for="user in profile.subscribers.data"
              :key="user.id"
              :user="user"
              @follow="followUser(user.id)"
            />
          </side-content>
        </template>
      </content-layout>
    </div>
  </div>
</template>

<script>
import UserList from "@/components/Subscriptions/UserList";
import ContentLayout from "@/layouts/ContentLayout";
import LazyLoadComponent from "@/components/LazyLoadComponent";
import UserItem from "@/components/Subscriptions/UserItem";
import SideContent from "@/components/Subscriptions/SideContent";

import FollowButton from "@/components/Subscriptions/FollowButton";

import { mergeDeep } from "@/helpers/utils";
import api from "@/api";
import followMixin from "@/mixins/followMixin";
export default {
  mixins: [followMixin],
  components: {
    UserList,
    ContentLayout,
    UserItem,
    SideContent,
    FollowButton
  },
  data() {
    return {
      profile: null,
      dropdown: false,
      links: [
        {
          text: "Tracks",
          path: {
            name: "profile.tracks"
          }
        },
        {
          text: "History",
          path: {
            name: "profile.history"
          }
        },
        {
          text: "Favourite",
          path: {
            name: "profile.favourite"
          }
        },
        { text: "...", path: "/" }
      ]
    };
  },
  computed: {
    location() {
      return [this.profile.profile.city, this.profile.profile.country].join(
        ", "
      );
    }
  },
  mounted() {
    // console.log("MOUNTED", this.$route);
  },

  extends: LazyLoadComponent(async (to, from, next, callback) => {
    try {
      const res = await api.getProfile(to.params.user);
      callback(function() {
        this.profile = res;
      });
      // console.log("Profile Page", res);
    } catch (err) {
      console.log("err", err.response);
      next({ name: "404" });
    }
  }),
  methods: {
    async followUser(id) {
      try {
        const newData = await this._followUser(
          this.profile.subscribers.data,
          id
        );

        this.profile.subscribers.data = [...newData];
      } catch (err) {
        console.log(err);
      }
    },
    async FollowProfile() {
      try {
        await this._followUser(this.profile, this.profile.id);
        // mergeDeep(this.profile, data);
      } catch (err) {
        console.log(err);
      }
    }
  }
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
