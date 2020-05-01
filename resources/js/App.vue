<template>
  <div id="app">
    <router-view></router-view>
  </div>
</template>

<script>
export default {
  async created() {
    await this.$auth.getUser().catch(err => console.log(err.response));
  },
  beforeDestroy() {
    // disconnect all pusher connections on page leave;
    this.$echo.disconnect();
  }
};
</script>

<style lang="scss"></style>
