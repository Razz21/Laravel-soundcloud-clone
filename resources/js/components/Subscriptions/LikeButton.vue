<script>
export default {
  functional: true,
  props: {
    isLiked: Boolean,
    total: Number,
    loading: Boolean
  },
  render(h, { props, data, listeners }) {
    const text = props.total ? h("span", [props.total]) : "";
    const icon = h("span", { class: "icon" }, [
      h("i", { class: "far fa-heart" })
    ]);

    const noop = () => {};
    const clickEvent = listeners.click || noop;
    return h(
      "button",
      {
        ...data,
        class: {
          "is-info is-outlined": props.isLiked,
          "is-loading": props.loading,
          button: true
        },
        on: {
          click: clickEvent
        }
      },
      [icon, text]
    );
  }
};
</script>
