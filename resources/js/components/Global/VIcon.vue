<script>
export default {
  props: {
    tooltip: String,
    text: [String, Number],
    icon: String
  },
  render(h, ctx) {
    const textContent = this.text
      ? h("span", { class: "help is-inline" }, [this.text])
      : "";
    const directives = this.tooltip
      ? { name: "tooltip", value: this.tooltip }
      : {};
    return h(
      "span",
      {
        staticClass: "icon-tooltip",
        directives: [directives]
      },
      [h("i", { class: this.icon }), textContent]
    );
  }
};
</script>

<style>
.icon-tooltip i + span {
  margin-left: 0.1rem;
}
.icon-tooltip + .icon-tooltip {
  margin-left: 0.5rem;
}
</style>
