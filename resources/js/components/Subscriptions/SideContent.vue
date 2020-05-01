<template functional>
  <div class="side-content">
    <div class="is-flex align-flex-end">
      <div class="title is-5">
        <slot name="title"></slot>
      </div>
      <div style="margin-left:auto" v-if="slots().right">
        <router-link :to="props.to" v-slot="{ href, navigate }">
          <a class="button is-small" :href="href" @click="navigate">
            <span>
              <slot name="right"></slot>
            </span>
            <span class="icon is-small">
              <i class="fas fa-angle-right"></i>
            </span>
          </a>
        </router-link>
      </div>
    </div>
    <hr />
    <slot></slot>
  </div>
</template>

<script>
export default {
  prop: { to: [String, Object] }
};
</script>

<style lang="scss">
.side-content {
  .title {
    margin: 0;
  }
  hr {
    margin: 0.5rem 0;
  }
  & + .side-content {
    margin-top: 2rem;
  }
}
</style>
