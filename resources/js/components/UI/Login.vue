<template>
  <div>
    <ValidationObserver ref="form" v-slot="{ invalid, handleSubmit }">
      <form @submit.prevent="handleSubmit(onSubmit)">
        <fieldset :disabled="loading">
          <validation-provider
            v-slot="{ errors, classes }"
            name="Login"
            rules="required"
            :vid="'username' || 'email'"
          >
            <div class="field">
              <div class="control">
                <input
                  class="input is-medium"
                  :class="classes"
                  v-model="form.login"
                  type="text"
                  required
                  placeholder="Email or Username"
                  autofocus
                  autocomplete="username"
                />
              </div>
              <p class="help is-danger" style="height:2rem">{{ errors[0] }}</p>
            </div>
          </validation-provider>
          <validation-provider
            name="Password"
            v-slot="{ errors, classes }"
            rules="required"
            vid="password"
          >
            <div class="field">
              <div class="control">
                <input
                  :class="classes"
                  class="input is-medium"
                  v-model="form.password"
                  type="password"
                  placeholder="Password"
                  autocomplete="current-password"
                />
              </div>
              <p class="help is-danger" style="height:2rem">{{ errors[0] }}</p>
            </div>
          </validation-provider>

          <div class="field " style="margin-top:1rem">
            <div class="control">
              <button
                type="submit"
                class="button is-link is-fullwidth is-medium"
                :disabled="invalid"
                :class="{ 'is-loading': loading }"
              >
                Submit
              </button>
            </div>
          </div>
        </fieldset>
      </form>
    </ValidationObserver>
    <div class="has-text-centered is-size-7" style="margin-top:2rem">
      <p class="has-text-weight-medium" style="margin-bottom:0.5rem">
        Need an account?
        <strong
          class="has-text-weight-bold is-clickable"
          @click="$emit('change', 'join')"
          >Sign up here.</strong
        >
      </p>
      <p class="has-text-weight-medium" style="margin-bottom:0.5rem">
        Forgot your Password?
        <strong
          class="has-text-weight-bold is-clickable"
          @click="$emit('change', 'pass.reset')"
          >Reset it here.</strong
        >
      </p>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    loading: Boolean
  },
  data() {
    return {
      form: {
        login: "",
        password: ""
      }
    };
  },
  methods: {
    onSubmit() {
      this.$emit("onSubmit", this.form);
    }
  }
};
</script>

<style></style>
