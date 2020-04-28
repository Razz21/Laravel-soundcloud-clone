<script>
export default {
  props: {
    tooltip: String,
    text: [String, Number],
    icon: String
  },
  render(h, ctx) {
    return h(
      "span",
      {
        staticClass: "icon-tooltip",
        directives: [{ name: "tooltip", value: this.tooltip }]
      },
      [
        h("i", { class: this.icon }),
        h("span", { class: "help is-inline" }, [this.text])
      ]
    );
  }
};
</script>

<style>
.icon-tooltip i {
  margin-right: 0.1rem;
}
.icon-tooltip + .icon-tooltip {
  margin-left: 0.5rem;
}
</style>
