<template>
  <div>
    <transition-group name="infinite-list">
      <slot v-bind:items="{ items }"></slot>
    </transition-group>
    <infinite-loading @infinite="infiniteHandler"></infinite-loading>
  </div>
</template>

<script>
import CommentItem from "@/components/Comments/CommentItem";
export default {
  components: { CommentItem },
  props: {
    url: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      items: [],
      meta: null,
      page: 1
    };
  },
  methods: {
    infiniteHandler($state) {
      this.$http
        .get(this.url, {
          params: {
            page: this.page
          }
        })
        .then(({ data }) => {
          if (data.data.length) {
            this.items.push(...data.data);
            this.page++;
            $state.loaded();
          } else {
            $state.complete();
          }
        });
    }
  }
};
</script>
