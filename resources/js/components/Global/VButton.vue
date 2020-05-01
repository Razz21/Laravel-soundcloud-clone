<script>
export default {
  functional: true,
  props: {
    icon: String
  },
  render(h, { data, props, slots, listeners }) {
    const defaultSlot = slots().default ? h("span", [slots().default]) : "";
    const icon = props.icon
      ? h("span", { class: "icon" }, [h("i", { class: props.icon })])
      : "";
    const noop = () => {};
    const clickEvent = listeners.click || noop;
    return h(
      "button",
      {
        ...data,
        class: ["button", data.class],
        on: {
          click: clickEvent
        }
      },
      [icon, defaultSlot]
    );
  }
};
</script>
