<template>
  <validation-provider v-slot="{ errors, classes }" v-bind="validationProps">
    <div class="field">
      <label class="label" v-bind="labelProps" v-if="label">{{ label }}</label>
      <div class="control" v-bind="controlProps">
        <div class="select is-fullwidth" v-bind="inputProps">
          <select
            ref="select"
            :value="value"
            @change="$emit('input', $event.target.value)"
            :class="classes"
            @focus="asyncData"
          >
            <option selected value=""></option>
            <option
              v-for="option in options"
              :key="option.id || option"
              :value="option.id"
              >{{ option.name || option }}</option
            >
          </select>
        </div>
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
    value: null,
    options: Array
  },
  methods: {
    asyncData(e) {
      this.$listeners.async && this.$emit("async");
    }
  }
};
</script>
