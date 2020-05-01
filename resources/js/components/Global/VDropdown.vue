<template>
  <div class="dropdown" :class="{ 'is-active': dropdown }">
    <div class="dropdown-trigger" v-click-outside="() => (dropdown = false)">
      <slot name="trigger" v-bind:trigger="{ toggle }"> </slot>
    </div>
    <div class="dropdown-menu" id="dropdown-menu" role="menu">
      <div class="dropdown-content">
        <slot> </slot>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      dropdown: false
    };
  },
  methods: {
    toggle() {
      this.dropdown = !this.dropdown;
    }
  }
};
</script>

<style></style>
