<template>
    <div class="container-fluid position-relative">
        <div class="row">
            <div class="col-6">
                <btg-stats-bar v-bind:isHero="true"
                               v-bind:health="100"
                               v-bind:strength="40"
                               v-bind:defence="22"
                               v-bind:speed="33"
                               v-bind:luck="33"
                               v-bind:attackSkills="['Rapid Strike']"
                               v-bind:defenceSkills="['Magic Shield']"
                               v-bind:currentHealth="100">

                </btg-stats-bar>
            </div>
            <div class="col-6">

            </div>
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
