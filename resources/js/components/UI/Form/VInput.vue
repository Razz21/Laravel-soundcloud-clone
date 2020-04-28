<template>
  <validation-provider
    v-slot="{ errors, classes, validate }"
    v-bind="validationProps"
  >
    <div class="field">
      <label class="label" v-bind="labelProps" v-if="label">{{ label }}</label>

      <div class="control" v-bind="controlProps">
        <slot v-bind:default="{ classes, validate }">
          <input
            :class="classes"
            v-bind="inputProps"
            class="input"
            v-bind:value="value"
            v-on:input="$emit('input', $event.target.value)"
          />
        </slot>
      </div>
      <p class="help" v-if="help">{{ help }}</p>
      <p class="help is-danger" style="height:1.2rem">{{ errors[0] }}</p>
    </div>
  </validation-provider>
</template>

<script>
import { inputMixin } from "@/mixins/inputMixin";
export default {
  mixins: [inputMixin],
  props: {
    value: null
  }
};
</script>

<style></style>
