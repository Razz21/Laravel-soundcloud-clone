<template>
  <div class="track">
    <div class="columns">
      <div class="column is-narrow track__cover">
        <v-img
          :src="track.cover"
          class="is-152x152"
          fallback="track-img-fallback"
        ></v-img>
      </div>
      <div class="column track__content">
        <div class="is-flex track__meta">
          <PlayButton
            style="margin-right:1rem"
            @click="togglePlay"
            :isPlaying="isPlaying"
          />
          <div class="track__title">
            <TrackTitle
              :track_slug="track.slug"
              :title="track.title"
              :user="track.user"
              titleClass="is-5"
              subtitleClass="is-6"
            />
          </div>
          <div class="track__tags" v-if="track.genre">
            <div class="tags">
              <a class="tag"> #{{ track.genre.name }} </a>
            </div>
          </div>
        </div>
        <div class="track__waveform">
          <Waveform
            :waveData="track.wave"
            :progress="progress"
            @onClick="changeAudioPosition"
          />
        </div>
        <div class="track__actions is-flex justify-space-between">
          <div
            class="buttons are-small has-text-grey-light"
            style="margin-bottom: 0;"
          >
            <LikeButton
              :isLiked="track.isLiked"
              :total="track.likes.total"
              @click="like"
              :disabled="$auth.cannot('like', track)"
            />
            <v-button icon="fas fa-plus" @click="addToPlaylist">Add</v-button>
            <v-dropdown>
              <template v-slot:trigger="{ trigger }">
                <v-button
                  icon="fas fa-ellipsis-h"
                  @click="trigger.toggle"
                ></v-button>
              </template>
              <template>
                <a href="#" class="dropdown-item">
                  Report
                </a>
                <a href="#" class="dropdown-item">
                  Block
                </a>
                <hr class="dropdown-divider" />
                <a href="#" class="dropdown-item">
                  Remove
                </a>
              </template>
            </v-dropdown>
          </div>
          <div class="is-flex has-text-grey-light">
            <VIcon
              :tooltip="`${track.plays} plays`"
              :text="track.plays"
              icon="fas fa-headphones"
            />
            <VIcon
              :tooltip="`Uploaded ${$moment(track.created_at).fromNow()}`"
              :text="$moment(track.created_at).fromNow()"
              icon="far fa-calendar-alt"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Waveform from "./Waveform";
import TrackTitle from "./TrackTitle";
import PlayButton from "./PlayButton";
import LikeButton from "@/components/Subscriptions/LikeButton";
import { getters, mutations, mapActions, mapGetters } from "@/store/player";
import api from "@/api";
import TrackMixin from "@/mixins/TrackMixin";
export default {
  mixins: [TrackMixin],
  components: {
    Waveform,
    TrackTitle,
    PlayButton,
    LikeButton
  },
  props: ["track", "isCurrentTrack"],
  data() {
    return {
      console
    };
  },

  computed: {
    ...mapGetters(["queue"])
  },
  methods: {
    ...mapActions(["addToQueue"]),
    addToPlaylist() {
      this.addToQueue(this.track);
    },
    async like() {
      try {
        const data = await this._auth(() => api.likeTrack(this.track.id));
        Object.assign(this.track, data);
      } catch (err) {
        // console.log(err.response);
      }
    }
  }
};
</script>

<style lang="scss">
.track {
  width: 100%;
  & + .track {
    padding-top: 1rem;
    border-top: 1px solid #eee;
  }
  &__content.column {
    display: flex;
    flex-direction: column;
    min-width: 0;
  }
  &__waveform {
    flex: 1 1 auto;
    height: 50px;
    margin: 1rem 0;
  }
  &__tags {
    align-self: flex-start;
    margin-left: auto;
  }
  &__title {
    margin-right: 1rem;
  }
  &__meta {
    align-items: flex-start;
  }
  &__meta,
  &__title {
    min-width: 0; //truncate long text
  }
  &__actions {
    .button {
      margin-bottom: 0;
    }
  }
}
</style>
