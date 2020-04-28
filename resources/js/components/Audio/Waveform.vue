<template>
  <div class="bars-wrap">
    <!-- this SVG is the "background" and progress bar -->

    <svg
      viewBox="0 0 100 100"
      class="waveform-container"
      preserveAspectRatio="none"
      ref="waveContainer"
      @click.stop="handleClick"
      @mousemove.stop="handleMouseMove"
      @mouseleave.stop="handleMouseLeave"
    >
      <linearGradient id="gradient" x1="0" y1="0" x2="0" y2="1">
        <stop offset="0.74" class="start-color" />
        <stop offset="0.75" class="middle-color" />
        <stop offset="0.76" class="end-color" />
      </linearGradient>

      <linearGradient id="gradient-progress" x1="0" y1="0" x2="0" y2="1">
        <stop offset="0.74" class="start-color" />
        <stop offset="0.75" class="middle-color" />
        <stop offset="0.76" class="end-color" />
      </linearGradient>

      <rect class="waveform-bg" x="0" y="0" height="100" width="100" />

      <rect
        ref="wavePreview"
        id="waveform-preview"
        class="waveform-preview"
        x="0"
        y="0"
        :width="preview"
        height="100"
      />
      <rect
        ref="waveProgress"
        id="waveform-progress"
        class="waveform-progress"
        x="0"
        y="0"
        :width="progress"
        height="100"
      />
    </svg>

    <!-- this SVG is the "clipping mask" - the waveform bars -->
    <svg height="0" width="0">
      <defs>
        <clipPath id="waveform-mask" ref="waveMask"></clipPath>
      </defs>
    </svg>
  </div>
</template>

<script>
export default {
  props: {
    waveData: {
      type: [Array, String],
      required: true
    },
    progress: {
      type: Number,
      default: 0
    }
  },
  data() {
    return {
      NUMBER_OF_BUCKETS: 100,
      SPACE_BETWEEN_BARS: 0.06,
      isMouseOver: false
    };
  },
  computed: {
    preview() {
      return this.isMouseOver ? 0 : this.progress || 0;
    }
  },
  methods: {
    createBars(buckets) {
      return buckets
        .map((bucket, i) => {
          let bucketSVGWidth = 100.0 / buckets.length;
          let bucketSVGHeight = bucket * 100.0;

          return `<rect
            x=${bucketSVGWidth * i + this.SPACE_BETWEEN_BARS / 2.0}
            y=${(100 - bucketSVGHeight) * 0.8}
            width=${bucketSVGWidth - this.SPACE_BETWEEN_BARS}
            height=${bucketSVGHeight} />`;
        })
        .join("");
    },
    handleClick(e) {
      // e = Mouse click event.
      var rect = this.$refs.waveContainer.getBoundingClientRect();
      var x = e.clientX - rect.left; //x position within the element.
      const currentPosition = x / rect.width;

      return this.$emit("onClick", currentPosition);
    },
    handleMouseMove(e) {
      this.isMouseOver = true;
      var rect = this.$refs.waveContainer.getBoundingClientRect();
      var x = e.clientX - rect.left; //x position within the element.
      const currentPosition = (x * 100) / rect.width;
      this.$refs.wavePreview.setAttribute("width", currentPosition);
      return this.$emit("onPreview", currentPosition);
    },
    handleMouseLeave(e) {
      this.isMouseOver = false;
      this.$refs.wavePreview.setAttribute("width", this.progress);
    }
  },
  mounted() {
    const waveMask = this.$refs.waveMask;
    const wavePreview = this.$refs.wavePreview;
    const waveProgress = this.$refs.waveProgress;
    const waveContainer = this.$refs.waveContainer;
    const waveData = Array.isArray(this.waveData)
      ? this.waveData
      : this.waveData.split(",").map(data => parseFloat(data));
    waveMask.innerHTML = this.createBars(waveData);
  }
};
</script>

<style lang="scss">
/* =========== bars ========== */

.bars-wrap {
  width: 100%;
  height: 100%;
  box-sizing: border-box;
  /* background: #282831; */
  color: #fff;
  --barcolor: #9a1d63;
}

.waveform-container {
  max-width: 100%;
  max-height: 150px;
  width: 100%;
  height: 100%;
  cursor: pointer;
  position: relative;
}

.waveform-bg {
  -webkit-clip-path: url("#waveform-mask");
  clip-path: url("#waveform-mask");
  fill: url("#gradient");
  overflow: hidden;
  position: relative;
  width: 100%;
  height: 100%;
}

.waveform-progress,
.waveform-preview {
  backface-visibility: hidden;
  transform: translate3d(0, 0, 0);
  perspective: 1000px;
  -webkit-clip-path: url("#waveform-mask");
  clip-path: url("#waveform-mask");
  // fill: #9a1d63;
  opacity: 0.5;
  fill: url("#gradient-progress");
}

.waveform-preview {
  opacity: 0.5;
  /* fill: #fa9dd8; */
}

#gradient-progress {
  .start-color {
    stop-color: #bd006b;
  }
  .middle-color {
    stop-color: #fff;
  }
  .end-color {
    stop-color: #9a1d6390;
  }
}

#gradient {
  .start-color {
    stop-color: #d3d3d3;
  }
  .middle-color {
    stop-color: #fff;
  }
  .end-color {
    stop-color: #d3d3d370;
  }
}
</style>
