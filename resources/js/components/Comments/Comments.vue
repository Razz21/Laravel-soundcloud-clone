<template>
  <div>
    <h2 class="subtitle is-5 is-capitalize mt-8">
      Comments
    </h2>
    <hr />

    <CommentForm :placeholder="`What do you think of ${track.title}?`" />
    <div class="mt-8">
      <transition-group name="infinite-list">
        <comment-item
          v-for="comment in comments"
          :key="comment.id"
          :comment="comment"
        ></comment-item>
      </transition-group>
      <infinite-loading @infinite="infiniteHandler"></infinite-loading>
    </div>
  </div>
</template>

<script>
import InfiniteList from "@/components/InfiniteList";
import CommentItem from "@/components/Comments/CommentItem";
import CommentForm from "@/components/Comments/CommentForm";
import api from "@/api";
export default {
  components: { CommentItem, CommentForm, InfiniteList },
  provide() {
    return { submit: this.submit };
  },
  props: { track: Object },
  data() {
    return {
      comments: [],
      meta: null,
      page: 1
    };
  },
  methods: {
    infiniteHandler($state) {
      api
        .getComments(this.track.id, {
          params: {
            page: this.page
          }
        })
        .then(res => {
          console.log(res);
          if (res.data.length) {
            this.comments.push(...res.data);
            this.page++;
            $state.loaded();
          } else {
            $state.complete();
          }
        });
    },
    appendComment(comment) {
      if (comment.parent_id) {
        const parent = this.comments.find(c => c.id === comment.parent_id);
        console.log("parent", parent);
        parent && parent.replies.unshift(comment);
      } else {
        this.comments.unshift(comment);
      }
    },
    submit(data) {
      console.log("data", data);
      return api
        .createComment(this.track.slug, data)
        .then(comment => {
          console.log("comment", comment);
          this.appendComment(comment);
          return true;
        })
        .catch(err => console.log(err));
    }
  }
};
</script>
