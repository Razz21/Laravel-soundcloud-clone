<script>
export default {
  functional: true,
  props: {
    isPlaying: {
      type: Boolean,
      default: false
    }
  },
  render(h, { props, listeners, data }) {
    const faicon = props.isPlaying ? "fas fa-pause" : "fas fa-play";
    const icon = h("i", { class: faicon });
    const iconWrap = h("span", { staticClass: "icon" }, [icon]);
    const noop = () => {};
    const clickEvent = listeners.click || noop;
    return h(
      "button",
      {
        ...data,
        class: "button button-play is-circular",
        on: {
          click: clickEvent
        }
      },
      [iconWrap]
    );
  }
};
</script>

<style lang="scss">
.button.button-play {
  &:active * {
    transform: scale(0.95);
  }
}
</style>
