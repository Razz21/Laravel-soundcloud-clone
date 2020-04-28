<template>
  <button class="btn btn-danger" @click="toggleSubscribe">
    {{ owner ? "" : subscribed ? "Unsubscribe" : "Subscribe" }}
    {{ subscriptions.length }} {{ owner ? "Subscriptions" : "" }}
  </button>
</template>

<script>
export default {
  props: {
    initSubs: {
      type: Array,
      required: true,
      default: () => []
    },
    profile: {
      type: Object,
      required: true,
      default: () => ({})
    }
  },
  data() {
    return {
      subscriptions: this.initSubs
    };
  },
  computed: {
    subscribed() {
      return __auth() && !!this.subscription;
    },
    owner() {
      return __auth() && this.profile.user_id == __auth().id;
    },
    subscription() {
      return this.subscriptions.find(sub => sub.user_id == __auth().id);
    }
  },
  methods: {
    toggleSubscribe() {
      if (!__auth()) {
        return alert("Login to subscribe");
      }
      if (this.owner) {
        return alert("Can not subscribe");
      }
      if (this.subscribed) {
        axios
          .delete(`/subscriptions/${this.subscription.id}`)
          .then(
            res =>
              (this.subscriptions = this.subscriptions.filter(
                sub => sub.id !== this.subscription.id
              ))
          )
          .catch(err => console.log(err));
      } else {
        axios
          .post(`/subscriptions/`, { profile_id: this.profile.id })
          .then(res => (this.subscriptions = [...this.subscriptions, res.data]))
          .catch(err => console.log(err));
      }
    }
  }
};
</script>

<style></style>
