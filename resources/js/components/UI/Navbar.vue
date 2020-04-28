<template>
  <nav class="navbar is-dark is-mobile">
    <div class="container is-fullhd">
      <div class="container is-fluid is-flex ">
        <div class="navbar-brand">
          <router-link :to="{ name: 'home' }" v-slot="{ href, navigate }">
            <a class="navbar-item" :href="href" @click="navigate">
              <img
                src="https://bulma.io/images/bulma-logo-white.png"
                alt="Bulma: a modern CSS framework based on Flexbox"
                width="112"
                height="28"
              />
            </a>
          </router-link>
        </div>

        <div class="navbar-start is-flex-mobile is-mobile">
          <router-link
            :to="{ name: 'tracks.upload' }"
            v-slot="{ href, navigate }"
          >
            <a
              class="navbar-item navbar-item-top"
              :href="href"
              @click="navigate"
            >
              Upload
            </a>
          </router-link>
        </div>

        <div class="navbar-end is-flex-mobile is-mobile">
          <div
            v-click-outside="() => (active = false)"
            @click="active = !active"
            class="navbar-item has-dropdown no-hover"
            :class="{ 'is-active': active }"
            v-if="$auth.isLogged"
          >
            <figure
              style="width: 40px; border-radius: 50%;"
              class="image navbar-link is-arrowless is-paddingless"
            >
              <img
                style="max-height: 100%; border: 2px solid #fff4;"
                class="is-rounded"
                :src="$auth.user.thumbnail"
              />
            </figure>
            <div class="navbar-dropdown is-boxed is-right">
              <router-link
                :to="{
                  name: 'profiles.show',
                  params: { user: $auth.user.url }
                }"
                v-slot="{ href, navigate }"
              >
                <a class="navbar-item" :href="href" @click="navigate">
                  Your Profile
                </a>
              </router-link>
              <hr class="navbar-divider" />
              <router-link
                :to="{ name: 'settings.profile' }"
                v-slot="{ href, navigate }"
              >
                <a class="navbar-item" :href="href" @click="navigate">
                  Settings
                </a>
              </router-link>
              <a class="navbar-item" @click="$auth.logout">
                Logout
              </a>
            </div>
          </div>
          <div class="navbar-item" v-else>
            <div class="field is-grouped">
              <p class="control">
                <button
                  class="button is-white"
                  @click="$emit('modal', 'login')"
                >
                  <span>
                    Login
                  </span>
                </button>
              </p>
              <p class="control">
                <button
                  class="button is-primary"
                  @click="$emit('modal', 'register')"
                >
                  <span>Join</span>
                </button>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  data() {
    return {
      active: false
    };
  }
};
</script>

<style lang="scss">
.navbar-end {
  margin-left: auto;
}
.navbar-start {
  margin-right: auto;
}
.navbar-item {
  display: flex !important;
  align-items: center !important;
  height: 100%;
  color: rgba(#000000, 0.7);
  &-top {
    color: white !important;
    &:hover {
      background-color: #292929 !important;
      color: rgba(white, 0.7) !important;
    }
  }
  &.no-hover {
    background-color: transparent !important;
  }
  &.is-active {
    & .navbar-dropdown {
      display: block;

      &.is-boxed {
        opacity: 1;
        pointer-events: auto;
        transform: translateY(0);
      }
    }
  }
}

.navbar-link:hover {
  background-color: #fff5;
}

.navbar-dropdown {
  font-size: 0.875rem;
  padding-bottom: 0.5rem;
  padding-top: 0.5rem;
  background-color: white;
  border-bottom-left-radius: 5px;
  border-bottom-right-radius: 5px;
  border-top: 2px solid #dbdbdb;
  box-shadow: 0 8px 8px rgba(10, 10, 10, 0.1);
  display: none;
  font-size: 0.875rem;
  left: 0;
  min-width: 100%;
  position: absolute;
  top: 100%;
  z-index: 20;
  &.is-boxed {
    border-radius: 5px;
    border-top: none;
    box-shadow: 0 8px 8px rgba(10, 10, 10, 0.1), 0 0 0 1px rgba(10, 10, 10, 0.1);
    display: block;
    opacity: 0;
    pointer-events: none;
    top: calc(100% + (-4px));
    transform: translateY(-5px);
    transition-duration: 86ms;
    transition-property: opacity, transform;
  }
  &.is-right {
    left: auto;
    right: 0;
  }

  & a.navbar-item {
    padding-right: 3rem;
  }
  & .navbar-item {
    padding: 0.375rem 1rem;
    white-space: nowrap;
  }
}

.navbar-divider {
  display: block !important;
}
</style>
