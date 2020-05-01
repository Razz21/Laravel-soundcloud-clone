<script>
import VButton from "@/components/Global/VButton";
export default {
  functional: true,
  extends: VButton,
  props: {
    isFollowed: Boolean
  },
  render(h, { props, data, listeners }) {
    const innerText = props.isFollowed ? "Unfollow" : " Follow ";
    const text = h("span", [innerText]);
    const icon = props.icon
      ? h("span", { class: "icon" }, [h("i", { class: props.icon })])
      : "";
    const noop = () => {};
    const clickEvent = listeners.click || noop;
    return h(
      "button",
      {
        ...data,
        class: {
          "is-outlined": props.isFollowed,
          "button is-info": true
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
