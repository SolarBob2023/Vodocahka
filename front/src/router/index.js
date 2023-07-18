import {createRouter, createWebHistory} from 'vue-router'
import HomeView from '../views/HomeView.vue'
import {useUserStore} from "@/stores/User";
import Cookies from "js-cookie";

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

router.beforeEach((to, from, next) => {
    const auth = Cookies.get('auth')
    const role = Number(Cookies.get('role'))
    const matched = to.matched.length > 0
    if (!matched) return next({name: 'home'})
    if (to.name === 'error') return next()
    if (auth) {
        if (to.name === 'login' || to.name === 'register') {
            return next({name: 'home'})
        }
        if (role === 2) return next()
        if (role === 1) {
            if (to.name !== 'home'){
                return next({name : 'home'})
            } else return next()
        }
    } else {
        if (to.name === 'login' || to.name === 'register' || to.name === 'home') {
            return next()
        } else return next({name: 'home'})
    }
})

export default router
