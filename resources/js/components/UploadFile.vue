<template>
  <div class="card">
    <div v-if="files.length" style="padding:1rem">
      <FileBox
        v-for="file in files"
        :key="file.idx"
        :file="file"
        :ref="`form_${file.idx}`"
        :state="getState(file.idx)"
        @remove="removeFile($event, file.idx)"
        @onSubmit="sendFile"
      />
    </div>

    <div
      v-else
      ref="uploadBox"
      class="file-box upload-box text-center"
      @drop.prevent="handleDropFiles"
      @dragover.prevent="$refs.uploadBox.classList.add('dragover')"
      @dragleave.prevent="$refs.uploadBox.classList.remove('dragover')"
    >
      <div class="file-box__input">
        <svg class="file-box__icon" viewBox="0 0 20 20">
          <path
            fill="none"
            d="M15.608,6.262h-2.338v0.935h2.338c0.516,0,0.934,0.418,0.934,0.935v8.879c0,0.517-0.418,0.935-0.934,0.935H4.392c-0.516,0-0.935-0.418-0.935-0.935V8.131c0-0.516,0.419-0.935,0.935-0.935h2.336V6.262H4.392c-1.032,0-1.869,0.837-1.869,1.869v8.879c0,1.031,0.837,1.869,1.869,1.869h11.216c1.031,0,1.869-0.838,1.869-1.869V8.131C17.478,7.099,16.64,6.262,15.608,6.262z M9.513,11.973c0.017,0.082,0.047,0.162,0.109,0.226c0.104,0.106,0.243,0.143,0.378,0.126c0.135,0.017,0.274-0.02,0.377-0.126c0.064-0.065,0.097-0.147,0.115-0.231l1.708-1.751c0.178-0.183,0.178-0.479,0-0.662c-0.178-0.182-0.467-0.182-0.645,0l-1.101,1.129V1.588c0-0.258-0.204-0.467-0.456-0.467c-0.252,0-0.456,0.209-0.456,0.467v9.094L8.443,9.553c-0.178-0.182-0.467-0.182-0.645,0c-0.178,0.184-0.178,0.479,0,0.662L9.513,11.973z"
          ></path>
        </svg>
        <input
          class="file-box__file"
          type="file"
          id="files"
          accept="audio/*"
          name="files"
          ref="files"
          multiple
          hidden
          @change="handleSelectFiles"
        />
        <label for="files">
          <strong>Choose a file</strong>
          <span class="file-box__dragdrop">or drag it here</span>
          .
        </label>
      </div>
      <div class="file-box__drop">
        Drop files here
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

import FileBox from "./FileBox";
import api from "@/api";
import axios from "axios";
const CancelToken = axios.CancelToken;
export default {
  components: { FileBox },
  data() {
    return {
      files: [],
      uploads: [],
      uploaded: false,
      fileStates: [],
      console: console
    };
  },
  computed: {},
  mounted() {
    this.listen();
  },
  beforeDestroy() {
    this.$echo.disconnect();
  },

  methods: {
    listen() {
      this.$echo.channel("upload").listen(".file.progress", job => {
        let state = this.fileStates.find(f => f.id === job.id);
        if (state) {
          Object.assign(state, job);
          console.log(state.progress_now, job.progress_now);
        }
      });
    },

    removeFile(cancel, id) {
      if (cancel) {
        if (confirm("Are you sure you want to cancel uploading?")) {
          cancel("Operation cancelled by the user");
        } else {
          return;
        }
      }
      this.files = this.files.filter(f => f.idx !== id);
      // remove status data
      this.fileStates = this.fileStates.filter(f => f.file_id !== id);
    },
    getState(id) {
      return this.fileStates.find(f => f.file_idx === id);
    },

    createProgress(fileArray) {
      this.fileStates = fileArray.map((f, idx) => {
        // object for monitoring file processing progress
        return {
          file_idx: f.idx, //identify file
          status: STATES.IDLE,
          progress_now: 0,
          id: "job_" + idx, //identify job,
          error: ""
        };
      });
    },

    sendFile(data) {
      const idx = data.get("file").idx || -1;
      const fileState = this.getState(idx);
      if (!fileState) return;
      fileState["status"] = STATES.UPLOADING;

      return api
        .uploadFile(data, {
          onUploadProgress: e => {
            fileState["progress_now"] = Math.ceil((e.loaded / e.total) * 100);
          },
          cancelToken: new CancelToken(function executor(c) {
            fileState["cancelToken"] = c;
          })
        })
        .then(res => {
          console.log("ok", res);
          // return jobState data from db;
          Object.assign(fileState, res);
        })
        .catch(err => {
          fileState["status"] = STATES.FAILED;
          if (axios.isCancel(err)) {
            fileState["error"] = err.message;
            console.log("im canceled", err);
          } else {
            if (err.response.data.errors) {
              console.log(this.$refs[`form_${idx}`][0].setErrors);
              return this.$refs[`form_${idx}`][0].setErrors(
                err.response.data.errors
              );
            }
          }
        });
    },
    handleDropFiles(e) {
      const droppedFiles = e.dataTransfer.files;
      return this.addFiles(droppedFiles);
    },
    handleSelectFiles() {
      return this.addFiles(this.$refs.files.files);
    },
    addFiles(files) {
      if (!files) return;
      try {
        this.files = Array.from(files)
          .filter(f => f.type.includes("audio"))
          .map(f => {
            f["idx"] = this.$uuid.v4();
            return f;
          });
        this.createProgress(this.files);
      } catch (err) {
        return;
      }
    }
  }
};
</script>

<style lang="scss">
.file-box {
  color: #0f3c4b;
  position: relative;
  background-color: #c8dadf;
  font-size: 1.25rem;
  padding: 100px 20px;
  $this: &;
  &.dragover {
    background-color: #eef3f5;
    #{$this}__input,
    #{$this}__icon {
      opacity: 0.2;
    }
    #{$this}__drop {
      opacity: 1;
    }

    &.upload-box {
      outline-offset: -20px;
      outline: 2px dashed #bdd1d3;
    }
  }
  &.upload-box {
    transition: 0.2s all ease;
    outline: 2px dashed #92b0b3;
    outline-offset: -10px;
  }
  &__drop {
    transition: 0.5s opacity ease;
    font-weight: 600;
    opacity: 0;
    font-size: 1.5rem;
  }

  &__icon {
    transition: 0.2s opacity ease;
    width: 100%;
    height: 100px;
    display: block;
    margin-bottom: 1rem;
    & path {
      fill: #92b0b3;
    }
  }
  &__input {
    transition: 0.2s opacity ease;
    box-sizing: border-box;
    text-align: center;
  }
  &__file + label {
    max-width: 80%;
    text-overflow: ellipsis;
    white-space: nowrap;
    cursor: pointer;
    display: inline-block;
    overflow: hidden;
    &:hover > strong {
      color: #32bae7;
    }
  }
  &__dragdrop {
    display: inline;
  }
}
</style>
