export default {
  methods: {
    _auth(callback) {
      return new Promise((resolve, reject) => {
        if (this.$auth.isLogged) {
          resolve(callback());
        } else {
          this.$auth.modal();
          reject();
        }
      });
    }
  }
};
