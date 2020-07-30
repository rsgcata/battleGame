<template>
  <div class="battle-details-container card">
    <div
      :class="[currentBattleState === battleStates.preparing ? 'd-flex' : 'd-none']"
      class="h-100 flex-column align-items-center justify-content-center"
    >
      <div>
        Preparing Game World ...
      </div>
      <div class="lds-ring">
        <div />
        <div />
        <div />
      </div>
    </div>

    <div
      :class="[counter !== 0 && battleRounds.length !== 0 ? 'd-flex' : 'd-none']"
      class="h-100 display-2 flex-column align-items-center justify-content-center"
    >
      {{ counter }}
    </div>

    <div
      ref="roundsContainer"
      v-show="counter === 0 && battleRounds.length !== 0"
      class="overflow-auto h-100 text-center align-middle"
    >
      <div
        v-for="(roundKey, index) in keysOfBattleRoundsToShow"
        :key="index"
        class="text-center"
      >
        <div class="font-weight-bold pt-3 pb-2">
          *** Round: {{ battleRounds[roundKey].roundNumber }} ***
        </div>

        <div v-if="battleRounds[roundKey].heroWasAttacker">
          <div
            v-if="battleRounds[roundKey].defenderWasLucky"
            class="pb-2"
          >
            The Monster got lucky and blocked all the damage dealt by the Hero!
          </div>
          <div v-else>
            <div class="pb-2">
              The Hero prepares to attack the Monster, dealing
              {{ battleRounds[roundKey].initialDamage }} damage.
            </div>
            <div
              v-if="battleRounds[roundKey].skillUsedByHero !== null"
              class="pb-2"
            >
              The Hero used the skill {{ battleRounds[roundKey].skillUsedByHero }}.
              His damage increased to {{ battleRounds[roundKey].finalDamageValue }}.
            </div>
            <div class="pb-2">
              The Monster now has {{ battleRounds[roundKey].defenderHealth }} health left (took
              {{ battleRounds[roundKey].finalDamageValue }} damage.
            </div>
          </div>
        </div>
        <div v-else>
          <div
            v-if="battleRounds[roundKey].defenderWasLucky"
            class="pb-2"
          >
            The Hero got lucky and blocked all the damage dealt by the Monster!
          </div>
          <div v-else>
            <div class="pb-2">
              The Monster prepares to attack the Hero, dealing
              {{ battleRounds[roundKey].initialDamage }} damage.
            </div>
            <div
              v-if="battleRounds[roundKey].skillUsedByHero !== null"
              class="pb-2"
            >
              The Hero used the skill {{ battleRounds[roundKey].skillUsedByHero }}.
              Monster's damage was decreased to {{ battleRounds[roundKey].finalDamageValue }}.
            </div>
            <div class="pb-2">
              The Hero now has {{ battleRounds[roundKey].defenderHealth }} health left (took
              {{ battleRounds[roundKey].finalDamageValue }} damage.
            </div>
          </div>
        </div>
      </div>

      <div v-show="keysOfBattleRoundsToShow.length === battleRounds.length"
      class="font-weight-bold h5">
        <div v-show="winner === 'hero'">
          Hero is the winner!
        </div>
        <div v-show="winner === 'monster'">
          Monster is the winner!
        </div>
        <div v-show="winner === null">
          It's a tie!
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'BattleResult',
    props: {
      battleRounds: {
        type: Array,
        required: false,
        default: function () {
          return []
        }
      },
      winner: {
        type: String,
        required: false,
        default: null
      }
    },
    data: function () {
      return {
        counter: 5,
        battleStates: {
          preparing: 1,
          counting: 2,
          running: 3
        },
        currentBattleState: 1,
        // A collection of battleRounds array keys, this way we don't have to copy
        // all the array
        keysOfBattleRoundsToShow: []
      }
    },
    watch: {
      battleRounds: function () {
        this.currentBattleState = this.battleStates.counting;
      },
      currentBattleState: function (newVal) {
        if (newVal === this.battleStates.counting) {
          let countdown = setInterval(() => {
            if (this.counter === 0) {
              this.currentBattleState = this.battleStates.running
              clearInterval(countdown)
            } else {
              this.counter--;
            }
          }, 1000);
        } else if (newVal === this.battleStates.running) {
          const pushRoundResult = (intervalToClear) => {
            this.keysOfBattleRoundsToShow.push(this.keysOfBattleRoundsToShow.length);

            this.$store.commit(
              'updateCharactersHealth',
              this.battleRounds[this.keysOfBattleRoundsToShow.length - 1]);

            if (this.keysOfBattleRoundsToShow.length === this.battleRounds.length) {
              this.$emit('battle-results-shown');

              clearInterval(intervalToClear);
            }

            this.$nextTick().then(() => {
              this.$refs['roundsContainer'].scrollTop = 9999;
            });
          };

          // Run first time without waiting
          pushRoundResult();

          let keyPusher = setInterval(() => {
            pushRoundResult(keyPusher);
          }, 2000);
        }
      }
    }
  }
</script>

<style scoped lang="scss">
  .battle-details-container {
    text-align: center;
    background-color: white;
    border-radius: 20px;
    border: 5px dashed #ddd;
  }

  .lds-ring {
    display: inline-block;
    position: relative;
    width: 80px;
    height: 80px;
  }

  .lds-ring div {
    box-sizing: border-box;
    display: block;
    position: absolute;
    width: 64px;
    height: 64px;
    margin: 8px;
    border: 8px solid;
    border-radius: 50%;
    animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
    border-color: #0b2e13 transparent transparent transparent;
  }

  .lds-ring div:nth-child(1) {
    animation-delay: -0.45s;
  }

  .lds-ring div:nth-child(2) {
    animation-delay: -0.3s;
  }

  .lds-ring div:nth-child(3) {
    animation-delay: -0.15s;
  }

  @keyframes lds-ring {
    0% {
      transform: rotate(0deg);
    }
    100% {
      transform: rotate(360deg);
    }
  }
</style>
