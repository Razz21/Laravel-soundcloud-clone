<template functional>
  <div class="columns" :class="data.staticClass" :style="data.staticStyle">
    <div class="column is-8">
      <slot></slot>
    </div>
    <div class="column">
      <slot name="side"></slot>
    </div>
  </div>
</template>

<script>
export default {};
</script>

<style></style>
