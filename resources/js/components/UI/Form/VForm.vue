<template>
  <ValidationObserver ref="form" v-slot="{ invalid, handleSubmit }">
    <form @submit.prevent="handleSubmit(onSubmit)" v-bind="formProps">
      <fieldset :disabled="disabled">
        <slot></slot>
      </fieldset>
    </form>
  </ValidationObserver>
</template>

<script>
export default {
  provide: {},
  props: {
    disabled: {
      type: Boolean,
      default: false
    },
    formProps: {
      type: Object,
      default: () => {}
    }
  },
  methods: {
    onSubmit() {
      this.$emit("onSubmit");
    }
  }
};
</script>

<style></style>
