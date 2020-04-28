<template>
  <article class="media">
    <div class="media-left">
      <v-img
        :src="($auth.isLogged && $auth.user.thumbnail) || null"
        class="is-64x64"
        fallback="has-background-grey-light"
      ></v-img>
    </div>
    <div class="media-content">
      <form @submit.prevent="submitForm">
        <fieldset :disabled="!$auth.isLogged">
          <div class="field">
            <p class="control">
              <textarea
                rows="3"
                class="textarea"
                :placeholder="placeholder"
                v-model.trim="body"
                :readonly="loading"
              ></textarea>
            </p>
          </div>
          <nav class="level">
            <div class="level-right" style="margin-left:auto">
              <div class="level-item ">
                <button
                  type="submit"
                  class="button is-info is-small"
                  :class="{ 'is-loading': loading }"
                >
                  Post comment
                </button>
              </div>
            </div>
          </nav>
        </fieldset>
      </form>
    </div>
  </article>
</template>

<script>
import VImg from "@/components/UI/General/VImg";
export default {
  components: { VImg },
  inject: ["submit"],
  props: {
    placeholder: String,
    parent_id: {
      type: [Number, String],
      validator: val => !!parseInt(val)
    }
  },
  data() {
    return {
      body: "",
      loading: false
    };
  },
  methods: {
    submitForm() {
      const data = {};
      if (this.body) {
        data.body = this.body;
        if (this.parent_id) {
          data.parent_id = this.parent_id;
        }
        this.loading = true;
        this.submit(data)
          .then(() => {
            this.$listeners.hide && this.$emit("hide");
          })
          .finally(() => {
            this.loading = false;
          });
      }
    }
  }
};
</script>
