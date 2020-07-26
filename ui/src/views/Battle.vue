<template>
  <div class="container-fluid position-relative">
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
      <div class="col-6" />
    </div>
  </div>
</template>

<script>
    import statsBar from '@/components/StatsBar';

    export default {
        name: "Battle",
        components: {
            'btg-stats-bar': statsBar
        }
    }
</script>

<style scoped>

</style>
