<template>
  <div class="file-upload-card">
    <div class="progress-bar">
      <div class="progress-bar__text help">
        <div class="is-capitalized">
          {{ processStatus }}
        </div>
        <div>{{ fileProgress }}%</div>
      </div>
      <progress
        class="progress is-small"
        :class="currentProgressState"
        :value="fileProgress"
        max="100"
        >{{ fileProgress }}</progress
      >
      <p class="help is-danger">
        {{ state.error || "" }}
      </p>
    </div>
    <v-form
      ref="form"
      @onSubmit="onSubmit"
      :disabled="isProcessed || isCompleted"
      :formProps="{ enctype: 'multipart/form-data' }"
    >
      <validation-provider
        v-slot="{ errors }"
        vid="file"
        name="File"
        :rules="{ maxFileSize: [file, 20480] }"
      >
        <div class="field">
          <input type="text" hidden name="file" :value="file.name" />
          <p class="help is-danger">{{ errors[0] }}</p>
        </div>
      </validation-provider>

      <div class="columns">
        <div class="column is-narrow" style="max-width:230px">
          <vImage
            label="Cover"
            :validationProps="{
              rules: { image: true, size: 1024, minDimensions: [300, 300] },
              vid: 'cover',
              name: 'Cover'
            }"
            help="Minimal dimensions: 300px x 300px. Max image size: 1MB."
            v-model="form.cover"
          />
        </div>
        <div class="column">
          <v-input
            :validationProps="{
              vid: 'slug' || 'title',
              rules: 'required|max:256',
              name: 'Title'
            }"
            v-model="form.title"
            label="Title"
            :inputProps="{
              placeholder: 'Track title',
              required: 'required'
            }"
            :labelProps="{ class: 'is-required' }"
          ></v-input>
          <VSelect
            @async="fetchGenres"
            label="Genre"
            :options="genres"
            v-model="form.genre"
            :inputProps="{ class: { 'is-loading': selectLoading } }"
            :validationProps="{ vid: 'genre', rules: '', name: 'Genre' }"
          />
          <VAutocomplete
            max="5"
            label="Tags"
            v-model="form.tags"
            :options="tagOptions"
            :controlProps="{ class: { 'is-loading': tagsLoading } }"
            :search-input.sync="search"
            :readonly="isProcessed || isCompleted"
            :validationProps="{
              name: 'Tags',
              vid: 'tags',
              rules: {
                maxArray: [form.tags, 5]
              }
            }"
          />
          <vTextarea
            max="2000"
            label="Description"
            v-model="form.description"
            :inputProps="{ placeholder: 'Describe track' }"
            :validationProps="{ vid: 'description', rules: 'max:2000' }"
          />
        </div>
      </div>
      <input type="submit" ref="submit" hidden />
    </v-form>
    <div class="is-flex justify-space-between">
      <p class="is-required help">required field</p>
      <div class="field is-grouped">
        <div class="control">
          <button
            class="button is-link is-small"
            :disabled="isProcessed || isCompleted"
            @click="$refs.submit.click()"
          >
            Submit
          </button>
        </div>
        <div class="control">
          <button
            v-if="isConverted"
            :disabled="isConverted"
            class="button is-link is-light is-small"
            @click="$emit('remove', isUploading ? state.cancelToken : null)"
          >
            Cancel
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
const STATES = {
  IDLE: "idle",
  PENDING: "queued",
  UPLOADING: "uploading",
  PROCESSING: "executing",
  COMPLETED: "finished",
  FAILED: "failed"
};
import VForm from "@/components/UI/Form/VForm";
import VInput from "@/components/UI/Form/VInput";
import VTextarea from "@/components/UI/Form/VTextarea";
import VAutocomplete from "@/components/UI/Form/VAutocomplete";
import VSelect from "@/components/UI/Form/VSelect";
import VImage from "@/components/UI/Form/VImage";
import { getters, actions } from "@/store";
export default {
  components: { VForm, VInput, VTextarea, VAutocomplete, VSelect, VImage },
  props: {
    file: {
      type: [Object, File],
      required: true
    },
    state: {
      type: Object,
      default: () => {}
    }
  },
  data() {
    return {
      form: {
        title: String(this.file.name).getFileName(),
        description: "",
        cover: null,
        genre: null,
        tags: []
        // published: false
      },
      selectLoading: false,
      search: "",
      tagsLoading: false,
      tagOptions: []
    };
  },

  computed: {
    genres() {
      return getters.genres();
    },
    isUploading() {
      return this.checkState(STATES.UPLOADING);
    },
    isConverted() {
      return this.checkState(STATES.PROCESSING);
    },
    isCompleted() {
      return this.checkState(STATES.COMPLETED);
    },
    isProcessed() {
      return [STATES.PENDING, STATES.UPLOADING, STATES.PROCESSING].includes(
        this.state["status"]
      );
    },
    processStatus() {
      switch (this.state["status"]) {
        case STATES.IDLE:
          return "Ready";
        case STATES.PENDING:
          return "Waiting";
        case STATES.UPLOADING:
          return "Uploading";
        case STATES.PROCESSING:
          return "Processing";
        case STATES.SUCCESS:
          return "Completed";
        case STATES.FAILED:
          return "Error";
        default:
          return this.state["status"];
      }
    },
    fileProgress() {
      return this.state["progress_now"];
    },
    currentProgressState() {
      switch (this.state.status) {
        case STATES.PROCESSING:
          return "is-warning";
        case STATES.COMPLETED:
          return "is-success";
        case STATES.FAILED:
          return "is-danger";
        default:
          return "is-info";
      }
    }
  },
  methods: {
    checkState(state) {
      return this.state.status === state;
    },
    setImage(e) {
      const image = e.target.files[0];
      // create preview
      this.$refs.preview.src = URL.createObjectURL(image);
      // set file
      this.form.cover = image;
    },
    onSubmit() {
      const form = new FormData();
      form.append("file", this.file);
      form.append("title", this.form.title);
      // append only filled inputs
      for (let [key, val] of Object.entries(this.form)) {
        if (val) {
          if (Array.isArray(val)) {
            val.forEach(i => {
              form.append(`${key}[]`, i);
            });
          } else {
            form.append(key, val);
          }
        }
      }
      this.$emit("onSubmit", form);
    },
    async fetchGenres() {
      if (!this.genres.length) {
        this.selectLoading = true;
        try {
          await actions.fetchGenres();
        } finally {
          this.selectLoading = false;
        }
      }
    },
    setErrors(err) {
      return this.$refs.form.$refs.form.setErrors(err);
    }
  },
  watch: {
    async search(val) {
      if (val.length < 2) {
        this.tagOptions = [];
        return;
      }
      if (this.tagsLoading) return;
      this.tagsLoading = true;
      try {
        this.tagOptions = await actions.fetchTags(val);
      } finally {
        this.tagsLoading = false;
      }
    }
  }
};
</script>

<style lang="scss">
.file-upload-card {
  border: 1px solid #eee;
  border-radius: $radius;
  padding: 1rem;
  & + .file-upload-card {
    margin-top: 1rem;
  }

  .progress-bar {
    width: 100%;
    .progress {
      margin-bottom: 0;
    }
    .error {
      height: 2rem;
      margin-bottom: 1rem;
    }
    &__text {
      display: flex;
      justify-content: space-between;
    }
  }
}
</style>
