<template>
    <div>
        <div class="d-flex">
            <div v-show="!isHero"
                 class="font-weight-bold">
                Monster
            </div>
            <div class="px-3"
                 v-bind:class="[!isHero ? 'mr-auto' : '']">
                <span class="font-weight-bold">Strength</span>: {{strength}}
            </div>
            <div class="px-3">
                <span class="font-weight-bold">Defence</span>: {{defence}}
            </div>
            <div class="px-3">
                <span class="font-weight-bold">Speed</span>: {{speed}}
            </div>
            <div class="px-3">
                <span class="font-weight-bold">Luck</span>: {{luck}}
            </div>
            <div v-show="isHero"
                 class="font-weight-bold"
                 v-bind:class="[isHero ? 'ml-auto' : '']">
                Hero
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "StatsBar",
        props: {}
    }
</script>

<style scoped>

</style>
