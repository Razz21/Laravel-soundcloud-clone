<template>
  <span class="popup">
    <div class="popup__trigger">
      <slot name="trigger"> </slot>
    </div>
    <span class="popup__content">
      <div class="box">
        <slot></slot>
      </div>
    </span>
  </span>
</template>

<script>
export default {};
</script>

<style lang="scss">
.popup {
  position: relative;
  display: inline-block;
  &:hover &__content {
    visibility: visible;
    opacity: 1;
    pointer-events: inherit;
  }
  &__trigger {
    cursor: pointer;
  }
  &__content {
    display: block;
    padding: 10px;
    visibility: hidden;
    opacity: 0;
    position: absolute;
    top: 0;
    left: 50%;
    transform: translate(-50%, -100%);
    transform-origin: bottom center;
    transition-property: opacity, visibility;
    transition: 0.2s ease-out;
    z-index: 101;

    .box {
      padding: 0.5rem;
    }

    &::after {
      content: "";
      display: block;
      position: absolute;
      margin: 0 auto;
      width: 0;
      height: 0;
      border: 5px solid transparent;
      border-top: 5px solid #fff;
      left: 50%;
      transform: translateX(-50%);
    }
  }
}
</style>
