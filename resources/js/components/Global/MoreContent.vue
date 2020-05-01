<template>
  <div class="hide is-relative" :style="{ height: containerHeight }">
    <div class="hide-overflow" @click="overflow = false" v-if="overflow">
      <button class="button is-info is-small">More</button>
    </div>
    <div ref="hide">
      <slot> </slot>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    height: {
      type: [String]
    }
  },
  computed: {
    containerHeight() {
      return this.overflow ? this.height : "";
    }
  },
  data() {
    return {
      overflow: true
    };
  },
  mounted() {
    if (this.$refs.hide.scrollHeight <= parseInt(this.height)) {
      this.overflow = false;
    }
  }
};
</script>

<style lang="scss">
.hide {
  overflow: hidden;
  &-overflow {
    cursor: pointer;
    position: absolute;
    height: 100%;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: flex-end;
    background: linear-gradient(
      0deg,
      rgba(255, 255, 255, 1) 1rem,
      rgba(255, 255, 255, 0) 5rem
    );
    .button {
      margin-bottom: 1rem;
    }
  }
}
</style>
