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

        <div v-if="isHero">
            <span class="font-weight-bold">Attack Skills</span>:
            <span v-for="skill in attackSkills">
                {{skill}}
            </span>
            <span class="font-weight-bold">| Defence Skills</span>:
            <span v-for="skill in defenceSkills">
                {{skill}}
            </span>
        </div>
        <div v-else>
            -
        </div>

        <div>
            <div class="font-weight-bold">
                Health (<span>{{health}}</span>):
            </div>
            <div class="progress">
                <div id="hero-health-bar"
                     class="progress-bar"
                     role="progressbar"
                     v-bind:aria-valuenow="currentHealth"
                     aria-valuemin="0"
                     aria-valuemax="100"></div>
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
                required: false
            },
            strength: {
                type: Number,
                required: false
            },
            defence: {
                type: Number,
                required: false
            },
            speed: {
                type: Number,
                required: false
            },
            luck: {
                type: Number,
                required: false
            },
            attackSkills: {
                type: Array,
                required: false
            },
            defenceSkills: {
                type: Array,
                required: false
            },
            currentHealth: {
                type: Number,
                required: true
            }
        }
    }
</script>
