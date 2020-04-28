export const inputMixin = {
  props: {
    label: {
      type: String,
      default: ""
    },
    help: String,
    validationProps: {
      type: Object,
      default: () => {}
    },
    inputProps: {
      type: Object,
      default: () => {}
    },
    labelProps: {
      type: Object,
      default: () => {}
    },
    controlProps: {
      type: Object,
      default: () => {}
    }
  }
};
