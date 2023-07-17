import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/LoginView.vue')
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('../views/RegisterView.vue')
    },
    {
      path: '/error',
      name: 'error',
      component: () => import('../views/ErrorView.vue')
    },
    {
      path: '/bills',
      name: 'bills',
      component: () => import('../views/BillView.vue')
    },
    {
      path: '/rates',
      name: 'rates',
      component: () => import('../views/RateView.vue')
    },
    {
      path: '/records',
      name: 'records',
      component: () => import('../views/RecordView.vue')
    },
    {
      path: '/residents',
      name: 'residents',
      component: () => import('../views/ResidentView.vue')
    },
  ]
})

export default router
