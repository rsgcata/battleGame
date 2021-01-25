import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
//import sampleBattleResult from '@/assets/sampleBattleResult';

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    battleResult: null,
    currentHeroHealth: null,
    currentMonsterHealth: null
  },
  mutations: {
    setBattleResult: function (state, battleResult) {
      state.battleResult = battleResult;
      state.currentHeroHealth = battleResult.heroStats.health;
      state.currentMonsterHealth = battleResult.monsterStats.health;
    },
    updateCharactersHealth: function (state, battleRound) {
      if (battleRound.heroWasAttacker) {
        state.currentMonsterHealth = battleRound.defenderHealth;
      } else {
        state.currentHeroHealth = battleRound.defenderHealth;
      }
    }
  },
  actions: {
    /**
     *
     * @param context
     * @returns {Promise<void>}
     * @throws Error
     */
    runNewBattle: async function (context) {
      let response;

      try {
        response = await axios.get('/battle/runBattle')
      } catch (e) {
        throw new Error('Could not run new battle. Bad API call.')
      }

      context.commit('setBattleResult', response.data)
    }
  },
  modules: {}
})
