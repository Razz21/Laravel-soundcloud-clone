<template>
  <div class="modal" :class="{ 'is-active': active }">
    <div class="modal-background"></div>
    <div class="modal-content ">
      <div class="box">
        <div class="is-flex" style="justify-content:flex-end">
          <button class="delete" aria-label="close" @click="close"></button>
        </div>

        <div class="tabs is-centered is-medium" v-if="authForm">
          <ul>
            <li
              v-for="item in tabs"
              :key="item.name"
              @click="open(item.name)"
              :class="{ 'is-active': item.name === component }"
            >
              <a>{{ item.text }}</a>
            </li>
          </ul>
        </div>
        <component
          :is="current"
          @change="open"
          ref="form"
          :loading="loading"
          @onSubmit="onSubmit"
        ></component>
      </div>
    </div>
  </div>
</template>

<script>
import Login from "./Login";
import Register from "./Register";
import { getters, mutations } from "@/store";
export default {
  data() {
    return {
      loading: false,
      tabs: [
        {
          name: "login",
          text: "Login"
        },
        {
          name: "register",
          text: "Register"
        }
      ]
    };
  },
  computed: {
    active() {
      return this.tabs.map(i => i.name).includes(this.component);
    },
    component() {
      return getters.authModal();
    },
    current() {
      switch (this.component) {
        case "register":
          return Register;
        default:
          return Login;
      }
    },
    authForm() {
      return ["login", "register"].includes(this.component);
    }
  },
  methods: {
    ...mutations,
    open(val) {
      return this.setAuthModal(val);
    },
    close() {
      return this.setAuthModal(false);
    },

    async login(data) {
      try {
        this.loading = true;
        await this.$auth.getCookie();
        await this.$auth.login(data);
        await this.$auth.getUser();
        this.close();
      } catch (err) {
        if (err.response.data.errors) {
          this.$refs.form.$refs.form.setErrors(err.response.data.errors);
          return;
        }
      } finally {
        this.loading = false;
      }
    },
    async register(data) {
      try {
        this.loading = true;
        await this.$auth.register(data);
        await this.$auth.getCookie();
        await this.$auth.getUser();
        this.close();
      } catch (err) {
        if (err.response.data.errors) {
          this.$refs.form.$refs.form.setErrors(err.response.data.errors);
          return;
        }
      } finally {
        this.loading = false;
      }
    },
    async onSubmit(data) {
      try {
        this.loading = true;
        if (this.component === "login") {
          await this.$auth.getCookie();
          await this.$auth.login(data);
          await this.$auth.getUser();
        } else if (this.component === "register") {
          await this.$auth.register(data);
          await this.$auth.getCookie();
          await this.$auth.getUser();
        } else {
          return;
        }
        this.close();
      } catch (err) {
        if (err.response.data.errors) {
          this.$refs.form.$refs.form.setErrors(err.response.data.errors);
          return;
        }
      } finally {
        this.loading = false;
      }
    }
  }
};
</script>

<style lang="scss">
.is-clickable {
  cursor: pointer;
  transition: color 0.2s ease;
  &:hover {
    color: #3f86b6;
  }
}
</style>
