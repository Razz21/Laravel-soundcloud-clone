<template>
  <div
    class="timeline-wrap"
    @click.stop="setPosition"
    @mousedown.stop="timelineStart"
  >
    <div
      class="timeline line"
      ref="timeline"
      :style="{ '--timeline': timeline }"
    ></div>
  </div>
</template>

<script>
export default {
  props: {
    audioPosition: [Number, String]
  },
  data() {
    return {
      drag: false,
      seek: 0
    };
  },
  computed: {
    timeline() {
      if (this.drag) {
        return this.seek * 100;
      }
      return this.audioPosition * 100;
    }
  },
  methods: {
    timelineStart() {
      this.drag = true;
      document.addEventListener("mousemove", this.movePlayHead, false);
      document.addEventListener("mouseup", this.timelineStop, false);
    },
    timelineStop(e) {
      e.stopPropagation();
      this.drag = false;
      this.setPosition(e);
      document.removeEventListener("mousemove", this.movePlayHead, false);
      document.removeEventListener("mouseup", this.timelineStop, false);
    },
    timelineMove(e) {
      e.stopPropagation();
      if (this.drag) {
        this.movePlayHead(e);
      }
    },
    setPosition(e) {
      this.movePlayHead(e);
      this.$emit("seek", this.seek);
      this.$emit("update:audioPosition", this.seek);
    },
    movePlayHead(e) {
      var rect = this.$refs.timeline.getBoundingClientRect();
      var x = e.clientX - rect.left;
      let seek = x / rect.width;
      this.seek = Math.min(Math.max(parseFloat(seek).toFixed(2), 0), 1);
    }
  }
};
</script>
