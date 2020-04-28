<template>
  <validation-provider v-slot="{ errors, classes }" v-bind="validationProps">
    <div class="field">
      <label class="label" v-bind="labelProps" v-if="label">{{ label }}</label>
      <div class="control" v-bind="controlProps" :class="classes">
        <textarea
          v-bind="inputProps"
          :maxlength="max"
          class="textarea"
          placeholder="Describe track"
          v-bind:value="value"
          v-on:input="$emit('input', $event.target.value.trim())"
        >
        </textarea>
      </div>
      <p class="help is-flex tag-input__help">
        <span v-if="help">{{ help }}</span>
        <span v-if="max > 0" class="count">{{ value.length }}/{{ max }}</span>
      </p>
      <p class="help is-danger" style="height:1.2rem">{{ errors[0] }}</p>
    </div>
  </validation-provider>
</template>

<script>
import { inputMixin } from "@/mixins/inputMixin";
export default {
  mixins: [inputMixin],
  props: {
    value: null,
    max: {
      type: [Number, String],
      default: 0,
      validator: val => parseInt(val) > 0
    }
  }
};
</script>

<style></style>
