<template>
  <div class="container-fluid position-relative min-vh-100">
    <btg-battle-result
      id="battle-details-container"
      :battle-rounds="battleRounds"
      :winner="winner"
      v-on:battle-results-shown="() => battleResultsShown = true"
    />

    <div class="row">
      <div class="col-12 col-md-6"
           :class="[battleResultsShown ? (winner === 'hero' ? 'winner' : 'loser') : '']">
        <btg-stats-bar
          v-if="heroStats"
          :is-hero="true"
          :health="heroStats.health"
          :strength="heroStats.strength"
          :defence="heroStats.defence"
          :speed="heroStats.speed"
          :luck="heroStats.luck"
          :attack-skills="heroStats.attackSkills"
          :defence-skills="heroStats.defenceSkills"
          :current-health="currentHeroHealth"
        />
      </div>
      <div class="col-12 col-md-6"
           :class="[battleResultsShown ? (winner === 'monster' ? 'winner' : 'loser') : '']">
        <btg-stats-bar
          v-if="monsterStats"
          :is-hero="false"
          :health="monsterStats.health"
          :strength="monsterStats.strength"
          :defence="monsterStats.defence"
          :speed="monsterStats.speed"
          :luck="monsterStats.luck"
          :current-health="currentMonsterHealth"
        />
      </div>
    </div>

    <div
      v-show="battleRounds.length !== 0"
      class="row"
    >
      <div class="col-6"
           :class="[battleResultsShown ? (winner === 'hero' ? 'winner' : 'loser') : '']">
        <img src="https://image.flaticon.com/icons/svg/2835/2835832.svg" alt="Hero Image">
      </div>
      <div class="col-6 text-right"
           :class="[battleResultsShown ? (winner === 'monster' ? 'winner' : 'loser') : '']">
        <img src="https://image.flaticon.com/icons/svg/2835/2835826.svg" alt="Monster Image">
      </div>
    </div>

    <div v-show="battleRounds.length !== 0">
      Icons made by
      <a
        href="https://www.flaticon.com/authors/eucalyp"
        title="Eucalyp"
      >
        Eucalyp
      </a>
      from
      <a
        href="https://www.flaticon.com/"
        title="Flaticon"
      >
        www.flaticon.com
      </a>
    </div>
  </div>
</template>

<script>
import statsBar from '@/components/StatsBar'
import battleResult from '@/components/BattleResult'

export default {
  name: 'Battle',
  components: {
    'btg-stats-bar': statsBar,
    'btg-battle-result': battleResult
  },
  data: function () {
    return {
      battleResultsShown: false
    }
  },
  computed: {
    currentHeroHealth: function () {
      return this.$store.state.currentHeroHealth
    },
    currentMonsterHealth: function () {
      return this.$store.state.currentMonsterHealth
    },
    heroStats: function () {
      return this.$store.state.battleResult !== null
        ? this.$store.state.battleResult.heroStats
        : null
    },
    monsterStats: function () {
      return this.$store.state.battleResult !== null
        ? this.$store.state.battleResult.monsterStats
        : null
    },
    battleRounds: function () {
      return this.$store.state.battleResult !== null
        ? this.$store.state.battleResult.roundsResults
        : []
    },
    winner: function () {
      return this.$store.state.battleResult !== null
        ? this.$store.state.battleResult.winner
        : null
    }
  },
  mounted: function () {
    setTimeout(() => {
      this.$store.dispatch('runNewBattle')
        .catch(() => {
          alert('Could not start a new battle. Try again by refreshing the page.')
        });
    }, 2000);
  }
}
</script>

<style scoped lang="scss">
#battle-details-container {
  position: absolute;
  top: 60%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 350px;
  height: 400px;
  z-index: 1000;
}

.winner {
  background-color: rgba(63, 191, 127, 0.8);
}

.loser {
  background-color: rgba(191, 63, 63, 0.8);
}
</style>
