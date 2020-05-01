<template>
  <div style="width:250px">
    <div class="is-flex flex-column" style="justify-content:space-around">
      <div class=" ml-2 mr-2">
        <h4
          class="is-size-7 has-text-white truncate mb-0"
          :title="track.get('title')"
        >
          {{ track.get("title") }}
        </h4>
        <h5 class="is-size-7 has-text-white  mb-0">
          by {{ track.user.screen_name }}
        </h5>
      </div>
    </div>
  </div>
</template>

<script>
import TrackModel from "@/helpers/TrackModel";
export default {
  props: {
    track: {
      type: TrackModel,
      required: true
    }
  }
};
</script>

<style></style>
