<template>
  <div>
    <transition-group name="infinite-list">
      <slot></slot>
    </transition-group>
    <infinite-loading @infinite="infiniteHandler"></infinite-loading>
  </div>
</template>

<script>
import CommentItem from "@/components/Comments/CommentItem";
export default {
  components: { CommentItem },
  model: {
    prop: "items",
    event: "update"
  },
  props: {
    url: {
      type: String,
      required: true
    },
    items: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
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
            const newItems = this.items.concat(data.data);
            this.$emit("update", newItems);
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
