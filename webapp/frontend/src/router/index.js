import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/cars',
    name: 'cars',
    component: () => import('@/pages/cars/cars.vue'),
  },
  {
    path: '/cars/:id',
    name: 'CarDetails',
    component: () => import('@/pages/cars/[_id]/index.vue'),
    props: true
  },
  {
    path: '/',
    name: 'home',
    component: () => import('@/pages/index.vue'),
  },
  {
    path: '/rents/renthistory',
    name: 'renthistory',
    component: () => import('@/pages/rents/renthistory.vue'),
  },
  {
    path: '/bills/AllBills',
    name: 'bills',
    component: () => import('@/pages/bills/AllBills.vue'),
  },
  {
    path: '/bills/fines',
    name: 'fines',
    component: () => import('@/pages/bills/fines.vue'),
  },
  
  {
    path: '/fleets/fleetIndex',
    name: 'fleets',
    component: () => import('@/pages/fleets/fleetIndex.vue'),
  },
  {
    path: '/logins/adminPage',
    name: 'adminlogin',
    component: () => import('@/pages/logins/adminPage.vue'),
  },
  {
    path: '/registers/registerPage',
    name: 'Registerpage',
    component: () => import('@/pages/registers/registerPage.vue'),
  },
];
export const router = createRouter({
  history: createWebHistory(),
  linkActiveClass: 'active',
  routes
})