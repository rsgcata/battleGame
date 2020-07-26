<template>
  <div>
    <div class="d-flex">
      <div
        v-show="!isHero"
        class="font-weight-bold"
      >
        Monster
      </div>
      <div :class="[!isHero ? 'ml-auto' : '']">
        <span class="font-weight-bold">Strength</span>: {{ strength }}
      </div>
      <div class="px-3">
        <span class="font-weight-bold">Defence</span>: {{ defence }}
      </div>
      <div class="px-3">
        <span class="font-weight-bold">Speed</span>: {{ speed }}
      </div>
      <div class="px-3">
        <span class="font-weight-bold">Luck</span>: {{ luck }}
      </div>
      <div
        v-show="isHero"
        class="font-weight-bold"
        :class="[isHero ? 'ml-auto' : '']"
      >
        Hero
      </div>
    </div>

    <div v-if="isHero">
      <span class="font-weight-bold">Attack Skills</span>:
      <span
        v-for="(skill, index) in attackSkills"
        :key="index"
      >
        {{ skill }}
      </span>
      <span class="font-weight-bold">| Defence Skills</span>:
      <span
        v-for="(skill, index) in defenceSkills"
        :key="index"
      >
        {{ skill }}
      </span>
    </div>
    <div v-else>
      -
    </div>

    <div>
      <div class="font-weight-bold">
        Health (<span>{{ health }}</span>):
      </div>
      <div class="progress">
        <div
          id="hero-health-bar"
          class="progress-bar"
          role="progressbar"
          :aria-valuenow="currentHealth"
          :style="{'width': currentHealth + '%'}"
          aria-valuemin="0"
          aria-valuemax="100"
        />
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: "StatsBar",
    props: {
      isHero: {
        type: Boolean,
        required: true
      },
      health: {
        type: Number,
        required: true
      },
      strength: {
        type: Number,
        required: true
      },
      defence: {
        type: Number,
        required: true
      },
      speed: {
        type: Number,
        required: true
      },
      luck: {
        type: Number,
        required: true
      },
      attackSkills: {
        type: Array,
        required: false,
        default: function () {
          return [];
        }
      },
      defenceSkills: {
        type: Array,
        required: false,
        default: function () {
          return [];
        }
      },
      currentHealth: {
        type: Number,
        required: true
      }
    }
  }
</script>
