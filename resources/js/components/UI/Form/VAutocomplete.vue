<template>
  <validation-provider v-slot="{ errors, classes }" v-bind="validationProps">
    <div class="field" v-click-outside="hideOptions">
      <label class="label" v-bind="labelProps" v-if="label">{{ label }}</label>
      <div
        class="control tag-input input"
        v-bind="{ ...controlProps, class: classes }"
      >
        <div
          class="tags has-addons tag-input-tag "
          v-for="tag in tags"
          :key="tag.name || tag"
        >
          <span class="tag is-info">{{ tag.name || tag }}</span>
          <a class="tag is-delete" @click="removeTag(tag)"></a>
        </div>
        <input
          v-bind="inputProps"
          type="text"
          placeholder="Add tag"
          class="tag-input-text"
          :class="classes"
          v-model.trim="input"
          @keydown.backspace="handleBackspace"
          @keydown.enter.prevent="handleInputEnter"
          @keyup.up.down.prevent="showOptions"
          @keyup.esc.prevent="hideOptions"
          @click="showOptions"
          @input="onSearch"
          @keyup.up.prevent="selectPrevOption"
          @keyup.down.prevent="selectNextOption"
        />
        <ul
          ref="options"
          v-show="optionsVisible"
          tabindex="-1"
          class="tag-input__dropdown"
        >
          <li
            v-for="(option, idx) in options"
            :key="option.name"
            :class="{ active: activeOptionIndex === idx }"
            :aria-selected="activeOptionIndex === idx"
            role="option"
            @click="handleOptionClick(option)"
            v-html="$options.filters.highlight(option.name || option, input)"
          >
            {{ option.name || option }}
          </li>
        </ul>
      </div>
      <div class="help is-flex tag-input__help">
        <div>
          <span v-if="help">{{ help }}</span>
          <p class="help is-danger" style="height:1.2rem">{{ errors[0] }}</p>
        </div>
        <span v-if="max > 0" class="count">{{ tags.length }}/{{ max }}</span>
      </div>
    </div>
  </validation-provider>
</template>

<script>
import { inputMixin } from "@/mixins/inputMixin";
export default {
  mixins: [inputMixin],
  model: {
    prop: "tags",
    event: "update"
  },
  props: {
    tags: { type: Array, required: true },
    max: {
      type: [Number, String],
      default: 0,
      validator: val => parseInt(val) > 0
    },
    options: {
      type: Array,
      default: () => []
    },
    searchInput: String,
    readonly: Boolean
  },
  data() {
    return {
      input: "",
      activeOptionIndex_: -1, //input field, >0 = dropdown option
      optionsVisible: false,
      timeout: null
    };
  },
  computed: {
    activeOptionIndex: {
      get() {
        return this.activeOptionIndex_;
      },
      set(val) {
        if (!Number.isInteger(val)) {
          val = -1;
        }
        this.activeOptionIndex_ = Math.min(
          this.options.length - 1,
          Math.max(-1, val)
        );
      }
    }
  },
  methods: {
    arrayRemove(el, val) {
      return !this.valueComparator(el, val);
    },
    getValue(el, prop) {
      return typeof el === "object" ? el[prop] : el;
    },
    valueComparator(el, val) {
      const nel = this.getValue(el, "name");
      const nval = this.getValue(val, "name");
      return nel.toLowerCase() === nval.toLowerCase();
    },
    tagsContain(tag) {
      return this.tags.some(t => {
        return this.valueComparator(t, tag);
      });
    },
    removeTag(tag) {
      !this.readonly &&
        this.$emit(
          "update",
          this.tags.filter(this.arrayRemove.bind(null, tag))
        );
    },
    handleInputEnter() {
      let newTag;
      if (this.activeOptionIndex < 0) {
        // try find input value in available options
        newTag =
          this.options.find(
            t => t.name.toLowerCase() === this.input.toLowerCase()
          ) || this.input;
      } else {
        // option selected
        newTag = this.options[this.activeOptionIndex];
      }
      if (!newTag || newTag.length === 0 || this.tagsContain(newTag)) {
        return this.clearInput();
      }
      return this.addTag(newTag);
    },
    handleOptionClick(option) {
      if (this.tagsContain(option)) {
        return this.clearInput();
      }
      this.addTag(option);
    },
    addTag(tag) {
      if (!tag || !this.readonly) return;
      if (this.max > 0 && this.tags.length >= this.max) {
        return this.clearInput();
      }
      this.$emit("update", [...this.tags, tag]);
      this.clearInput();
      this.hideOptions();
    },
    clearInput() {
      this.input = "";
      this.$emit("update:search-input", this.input);
    },
    hideOptions() {
      this.optionsVisible = false;
      this.activeOptionIndex = -1;
    },
    showOptions() {
      this.optionsVisible = true;
    },
    async reset() {
      this.hideOptions();
      await this.$nextTick();
    },
    handleBackspace(e) {
      if (this.input.length === 0) {
        this.$emit("update", this.tags.slice(0, -1));
      }
    },
    selectPrevOption() {
      if (this.activeOptionIndex > -1) {
        this.activeOptionIndex--;
      } else {
        this.activeOptionIndex = this.options.length - 1;
      }
    },
    selectNextOption() {
      if (this.optionsVisible) {
        if (this.activeOptionIndex < this.options.length - 1) {
          this.activeOptionIndex++;
        } else {
          this.activeOptionIndex = -1;
        }
      } else {
        this.showOptions();
      }
    },
    onSearch() {
      if (this.max > 0 && this.tags.length >= this.max) {
        return;
      }
      this.activeOptionIndex = -1; //reset select
      this.showOptions();
      if (this.timeout) clearTimeout(this.timeout);
      // debounce input
      this.timeout = setTimeout(() => {
        this.$emit("update:search-input", this.input);
      }, 200);
    }
  }
};
</script>

<style lang="scss">
.tag-input {
  align-items: center;
  display: flex;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  background-color: #fff;
  border-width: 1px;
  border-radius: 0.25rem;
  position: relative;
  &.input {
    // get input styling
    height: auto;
    padding: 0.5rem 0.25rem 0.25rem 0.75rem !important;
  }
  &__help {
    .count {
      margin-left: auto;
    }
  }
  &__dropdown {
    background-color: #fff;
    position: absolute;
    width: 100%;
    top: 100%;
    left: 0;
    z-index: 50;
    border: inherit;
    list-style: none;
    font-weight: inherit;
    color: inherit;
    li {
      padding: 0.25rem 0.5rem;
      > mark {
        background-color: #ffdd38;
      }
      &.active {
        background-color: #ddd;
      }
      &:hover {
        background-color: #ddd5;
      }
    }
    li + li {
      border-top: 1px solid #e2e0e0;
    }
  }
}

.tag-input-tag {
  display: inline-flex;
  line-height: 1;
  align-items: center;
  user-select: none;
  margin-right: 0.5rem;
  margin-bottom: 0.25rem !important;
  .tag {
    margin-bottom: 0 !important;
  }
}

.tag-input-text {
  flex: 1;
  outline: 0;
  font-size: 100%;
  min-width: 8rem;
  box-shadow: none;
  border: none;
  color: inherit;
  height: 100%;
  margin-bottom: 0.25rem;
  background-color: transparent;
}
</style>
