<template>
  <validation-provider
    v-slot="{ errors, classes, validate }"
    v-bind="validationProps"
  >
    <div class="field">
      <label class="label" v-bind="labelProps" v-if="label">{{ label }}</label>

      <div class="control" v-bind="controlProps">
        <figure
          class="image upload-image"
          :style="{ width: imgWidth, height: imgHeight }"
          v-bind="figureProps"
        >
          <img ref="preview" v-bind="imgProps" />
          <div class="overlay" @click="$refs.cover.click()">
            <i class="fas fa-camera fa-4x"></i>
          </div>
        </figure>
        <input
          v-bind="inputProps"
          hidden
          type="file"
          accept="image/*"
          ref="cover"
          name="image"
          @change="validate($event) && setImage($event)"
        />
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
  model: {
    prop: "image",
    event: "change"
  },

  props: {
    image: null,
    width: {
      type: [Number, String],
      default: 200,
      validator: val =>
        /^(auto|0)$|^[0-9]+(px|em|rem|ex|%|in|cm|mm|pt|pc|vh|vw|vmin,vmax)?$/.test(
          val
        )
    },
    height: {
      type: [Number, String],
      default: 200,
      validator: val =>
        /^(auto|0)$|^[0-9]+(px|em|rem|ex|%|in|cm|mm|pt|pc|vh|vw|vmin,vmax)?$/.test(
          val
        )
    },
    figureProps: { type: Object, default: () => {} },
    imgProps: { type: Object, default: () => {} }
  },
  computed: {
    imgWidth() {
      return this.getValidStyleValue(this.width);
    },
    imgHeight() {
      return this.getValidStyleValue(this.height);
    }
  },

  methods: {
    getValidStyleValue(val) {
      if (/^\d+$/.test(val)) {
        return val + "px"; //default, if no unit given
      }
      return val;
    },
    setImage(e) {
      const image = e.target.files[0];
      // create preview
      this.$refs.preview.src = URL.createObjectURL(image);
      // set file
      this.$emit("change", image);
    }
  }
};
</script>

<style></style>
