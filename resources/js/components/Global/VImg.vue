<script>
export default {
  functional: true,
  props: ["src", "rounded", "fallback"],

  render(h, { data, props, slots }) {
    const image =
      props.src &&
      h("img", {
        attrs: { src: props.src },
        class: { "is-rounded": props.rounded }
      });

    return h(
      "figure",
      {
        ...data,
        class: [
          { image: true, "has-radius-large": true, "is-clipped": true },
          props.fallback
        ]
      },
      [image]
    );
  }
};
</script>

<style></style>
