<template>
  <div id="app">
    <Navbar @modal="toggleModal" />
    <AuthModal ref="modal" v-if="!$auth.isLogged" />

    <main class="container is-fullhd pt-3">
      <router-view></router-view>
    </main>
    <PanelPlayer />
    <Footer />
  </div>
</template>

<script>
import AsyncComponent from "@/components/AsyncComponent";
import Navbar from "@/components/UI/Navbar";
import Footer from "@/components/UI/Footer";
import AuthModal from "@/components/UI/AuthModal";
import PanelPlayer from "@/components/Audio/PanelPlayer";
// const AuthModal = AsyncComponent(import("@/components/UI/AuthModal"));
// const PanelPlayer = AsyncComponent(import("@/components/Audio/PanelPlayer"));
import { getters } from "@/store/player";
export default {
  components: { Navbar, Footer, AuthModal, PanelPlayer },
  methods: {
    toggleModal(val) {
      this.$refs.modal.open(val);
    }
  },
  computed: {
    currentTrack() {
      return getters.get("currentTrack");
    }
  }
};
</script>

<style lang="scss">
#app {
  position: relative;
}
</style>
