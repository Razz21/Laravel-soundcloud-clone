<template>
  <article class="media">
    <figure class="media-left">
      <v-img
        :src="comment.user.thumbnail"
        class="is-48x48"
        fallback="has-background-grey-light"
      ></v-img>
    </figure>
    <div class="media-content">
      <div class="content">
        <div>
          <!-- <profile-popup :user="comment.user"> -->
          <strong>{{ comment.user.screen_name }}</strong>
          <!-- </profile-popup> -->
          <span class="ml-3">
            <small class="is-size-7">
              {{ $moment(comment.created_at).fromNow() }}
            </small>
          </span>
          <a
            v-if="!reply"
            class="is-link"
            style="float:right"
            @click="toggleForm"
          >
            <span class="icon">
              <i class="fas fa-reply"></i>
            </span>
          </a>
          <div class="is-size-7">
            {{ comment.body }}
          </div>
          <br />
        </div>
      </div>
      <template v-if="!reply">
        <CommentForm :parent_id="comment.id" v-if="form" @hide="form = false" />
        <transition-group name="infinite-list">
          <comment-item
            v-for="reply in comment.replies"
            :key="reply.id"
            :comment="reply"
            reply
          ></comment-item>
        </transition-group>
      </template>
    </div>
  </article>
</template>

<script>
import VImg from "@/components/UI/General/VImg";
import ProfilePopup from "@/components/Profile/ProfilePopup";
import CommentForm from "./CommentForm";
export default {
  components: {
    VImg,
    ProfilePopup,
    CommentForm,
    CommentItem: () => import("./CommentItem")
  },
  props: {
    comment: { type: Object, required: true },
    reply: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      form: false
    };
  },
  methods: {
    toggleForm() {
      this.form = !this.form;
    }
  }
};
</script>

<style></style>
