<template>
  <div class="container-fluid position-relative">
    <btg-battle-result id="battle-details-container" />

    <div class="row">
      <div class="col-6">
        <btg-stats-bar
          :is-hero="true"
          :health="100"
          :strength="40"
          :defence="22"
          :speed="33"
          :luck="33"
          :attack-skills="['Rapid Strike']"
          :defence-skills="['Magic Shield']"
          :current-health="100"
        />
      </div>
      <div class="col-6">
        <btg-stats-bar
          :is-hero="false"
          :health="100"
          :strength="40"
          :defence="22"
          :speed="33"
          :luck="33"
          :current-health="100"
        />
      </div>
    </div>

    <div class="row">
      <div class="col-6">
        <img src="https://image.flaticon.com/icons/svg/2835/2835832.svg">
      </div>
      <div class="col-6 text-right">
        <img src="https://image.flaticon.com/icons/svg/2835/2835826.svg">
      </div>
    </div>

    <div>
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
  import statsBar from '@/components/StatsBar';
  import battleResult from '@/components/BattleResult';

  export default {
    name: "Battle",
    components: {
      'btg-stats-bar': statsBar,
      'btg-battle-result': battleResult
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
</style>
