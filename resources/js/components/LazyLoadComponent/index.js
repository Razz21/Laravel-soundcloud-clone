export default loadData => {
  let loaderCallback = () => {};
  const loadRoute = (to, from, next) => {
    loadData(to, from, next, callback => {
      loaderCallback = callback;
      next();
    });
  };
  return {
    beforeRouteEnter: loadRoute,
    beforeRouteUpdate(to, from, next) {
      /*
      do not trigger async metchod on child route update;
      assume this component do not change
      */
      if (
        to.matched[1].meta.key(this.$route) ===
        from.matched[1].meta.key(this.$route)
      ) {
        next();
      } else {
        loadData(to, from, next, callback => {
          loaderCallback = callback;
          next();
        });
      }
    },
    created: function() {
      loaderCallback.apply(this);
    },
    watch: {
      $route: function() {
        loaderCallback.apply(this);
      }
    }
  };
};
