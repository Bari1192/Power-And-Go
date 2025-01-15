import { createRouter, createWebHistory } from 'vue-router'
import { routes } from 'vue-router/auto-routes'
import { equal } from 'fast-deep-equal';


export const router = createRouter({
  history: createWebHistory(),
  linkActiveClass: 'active',
  routes
})