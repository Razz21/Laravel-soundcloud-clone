<template>
  <div class="queue has-background-dark">
    <div class="queue-panel" v-if="panel">
      <draggable
        :list="tracks"
        v-bind="dragOptions"
        tag="ul"
        @end="updateQueueOrder"
      >
        <transition-group type="transition" name="queue-list" mode="out-in">
          <QueueItem
            v-for="(track, idx) in tracks"
            :track="track"
            :key="track.uid"
            @delete="removeFromQueue(idx)"
            @changeTo="playSelected(idx)"
            :isCurrentTrack="currentItem === track.uid"
          />
        </transition-group>
      </draggable>
    </div>
    <VButton
      icon="fas fa-list-ul"
      class="is-dark"
      @click="panel = !panel"
      :class="{ 'is-active': panel }"
    ></VButton>
  </div>
</template>

<script>
import QueueItem from "./QueueItem";
import draggable from "vuedraggable";
import {
  mapActions,
  getters,
  mutations,
  mapPropsGetterSetters,
  mapGetters,
  actions
} from "@/store/player";
export default {
  components: { QueueItem, draggable },
  data() {
    return {
      panel: false
    };
  },
  computed: {
    tracks() {
      return getters.get("queue").elements;
    },
    dragOptions() {
      return { handle: ".handle", animation: 150, ghostClass: "ghost" };
    },
    currentItem() {
      return getters.get("currentQueueUid");
    }
  },
  methods: {
    ...mapActions(["removeFromQueue", "playSelected", "updateQueueOrder"])
  },
  async created() {
    await actions.getQueue();
  }
};
</script>

<style lang="scss">
.queue {
  position: relative;

  opacity: 1;
  &-panel {
    min-width: 0;
    width: 350px;
    height: 400px;
    overflow-x: hidden;
    overflow-y: auto;
    scrollbar-width: thin;
    border-radius: $radius;
    border: 1px solid #555;
    z-index: -1;

    padding: 0.8rem 0.4rem;
    opacity: 1;
    position: absolute;
    right: 0;
    bottom: 100%;

    background-color: inherit;
    figure::before {
      font-size: 1rem;
    }
    .media + .media {
      margin-top: 0;
      padding-top: 0.5rem;
    }
    .media-content {
      min-width: 0;
    }
  }
  &-item {
    transition: filter 0.3s;
    padding: 0.5rem;
    display: flex;
    align-items: center;
    width: 100%;
    & + .queue-item {
      border-bottom: 1px solid #555;
    }
    &.active {
      background-color: #e800fd50;
    }
    .text {
      font-weight: 400;
      color: white;
    }

    h5,
    h4 {
      margin-bottom: 0.25em !important;
    }
    &:hover {
      filter: brightness(1.1);
    }
    .handle {
      cursor: move;
    }

    .media {
      margin: 0 0.5rem;
      flex: 1;
      min-width: 0;
    }
  }
}

.queue-list {
  &-enter-active,
  &-leave-active {
    transition: opacity 0.3s;
  }
  &-leave-active {
    position: absolute;
  }
  &-enter,
  &-leave-to {
    opacity: 0;
  }

  // &-move {
  // transition: transform 0.3s;
  // }
}

.ghost {
  opacity: 0.2;
}
</style>
