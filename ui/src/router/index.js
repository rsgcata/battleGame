import Vue from 'vue';
import VueRouter from 'vue-router';
import Battle from '../views/Battle.vue';

Vue.use(VueRouter);

const routes = [
  {
    path: '/',
    name: 'Battle',
    component: Battle
  }
];

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
});

export default router;
