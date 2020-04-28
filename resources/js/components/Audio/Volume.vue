<template>
  <div class="volume has-background-dark">
    <div
      class="volume-panel"
      @click.stop="moveVolHead"
      @mousedown.stop="volumeStart"
    >
      <div
        class="volumeline line"
        ref="volumeline"
        :style="{ '--volume': volume }"
      ></div>
    </div>
    <VButton
      :icon="volumeIcon"
      class="is-dark"
      @click.stop="$emit('mute')"
    ></VButton>
  </div>
</template>

<script>
import VButton from "@/components/UI/General/VButton";
export default {
  components: { VButton },
  props: {
    volume: Number
  },
  data() {
    return {
      drag: false
    };
  },
  computed: {
    volumeIcon() {
      const icon = "fas fa-volume-";
      const type =
        this.volume > 0 ? (this.volume > 60 ? "up" : "down") : "mute";
      return icon + type;
    }
  },
  methods: {
    volumeStart() {
      this.drag = true;
      document.addEventListener("mousemove", this.moveVolHead, false);
      document.addEventListener("mouseup", this.volumeStop, false);
    },
    volumeStop(e) {
      e.stopPropagation();
      this.drag = false;
      this.moveVolHead(e);
      document.removeEventListener("mousemove", this.moveVolHead, false);
      document.removeEventListener("mouseup", this.volumeStop, false);
    },
    volumeMove(e) {
      e.stopPropagation();
      if (this.drag) {
        this.moveVolHead(e);
      }
    },
    moveVolHead(e) {
      var rect = this.$refs.volumeline.getBoundingClientRect();
      var y = rect.bottom - e.clientY;
      let volume = (y * 100) / rect.height;
      volume = Math.min(Math.max(parseFloat(volume).toFixed(2), 0), 100);
      this.$emit("update:volume", volume);
      // console.log(this.player.volume, volume);
    }
  }
};
</script>

<style></style>
