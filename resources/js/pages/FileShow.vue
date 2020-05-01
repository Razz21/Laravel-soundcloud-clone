<template>
  <div>
    <div class="has-background-black-ter">
      <div class="inner-container ">
        <AudioPlayer :track="track" />
      </div>
    </div>
    <div class="inner-container  pt-8">
      <content-layout>
        <template>
          <h2
            class="subtitle is-7 is-uppercase has-text-link has-text-weight-semibold mb-3"
          >
            Tagged
          </h2>
          <div class="tags">
            <a class="tag" v-for="tag in track.tags" :key="tag.id">
              #{{ tag.name }}
            </a>
          </div>
          <div>
            <p style="white-space:pre-line;" class="subtitle is-6">
              {{ track.description }}
            </p>
          </div>
          <Comments :track="track" />
        </template>
      </content-layout>
    </div>
  </div>
</template>

<script>
import AudioPlayer from "@/components/AudioPlayer";
import ContentLayout from "@/layouts/ContentLayout";
import CommentItem from "@/components/Comments/CommentItem";
import CommentForm from "@/components/Comments/CommentForm";
import api from "@/api";
import LazyLoadComponent from "@/components/LazyLoadComponent";
import InfiniteList from "@/components/InfiniteList";
import Comments from "@/components/Comments/Comments";
export default {
  components: {
    AudioPlayer,
    ContentLayout,
    CommentItem,
    CommentForm,
    InfiniteList,
    Comments
  },
  extends: LazyLoadComponent(async (to, from, next, callback) => {
    const { user, track } = to.params;

    try {
      const res = await api.getUserTrack(user, track);
      callback(function() {
        this.track = res;
      });
      console.log(res);
    } catch (err) {
      console.log("err.response", err.response);
      next({ name: "404" });
    }
  }),
  data() {
    return {
      track: undefined
    };
  },
  mounted() {},

  computed: {}
};
</script>

<style></style>
