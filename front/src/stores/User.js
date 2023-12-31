import {defineStore} from 'pinia'
import { mapActions } from 'pinia'
import {useErrorStore} from "@/stores/Error";
import api from "@/api";
import router from "@/router";
import App from "@/App.vue";
import Cookies from "js-cookie";

export const useUserStore = defineStore('user', {
    state: () => ({
        user: {
            name: null,
            email: null,
            surname: null,
            role: null,
            patronymic: null
        },
        errors: {
            email: null,
            name: null,
            surname: null,
            patronymic: null,
            password: null,
            password_confirmation: null
        },
        isAuth: false,
        isLoaded : false,
    }),
    getters: {},
    actions: {
        ...mapActions(useErrorStore,{ storeError: 'storeError' }),

        resetUser(){
            this.user.name = null
            this.user.email = null
            this.user.surname =null
            this.user.role = null
            this.user.patronymic = null
            this.isAuth = false
        },
        resetErrors(){
            this.errors.email = null
            this.errors.name = null
            this.errors.surname = null
            this.errors.patronymic = null
            this.errors.password = null
            this.errors.password_confirmation = null
        },

        async register() {
            try {
                const response = await api.post('/api/user', this.user)
                if (response){
                    if (response.status === 201 && response.data.data){
                        this.resetUser();
                        this.resetErrors();
                        this.user = response.data.data
                        this.isAuth = true
                        Cookies.set('auth', 'true',{ expires: 2, sameSite: 'strict'})
                        Cookies.set('role', this.user.role, { expires: 2,sameSite: 'strict'})
                        router.push({ name: 'home' })
                    }
                }

            } catch (error){
                // ошибка валидации
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors
                } else {
                    this.storeError(error)
                    router.push({name: 'error'})
                }
            }
        },

        async logout() {
            try {
                const response = await api.get('/api/user/logout')
                if (response.status === 200){
                    this.$reset();
                    Cookies.remove('auth')
                    Cookies.remove('role')
                    router.push({name: 'login'})
                }
            } catch (error) {
                this.storeError(error)
                router.push({name: 'error'})
            }

        },

        async getUser() {
            this.isLoaded = true
            //this.sleep(1000);
            try {
                await api.get('/api/sanctum/csrf-cookie')
                const response = await api.get('/api/user/')

                if (response.status === 200 && response.data.data) {
                    this.user = response.data.data
                    this.isAuth = true
                    Cookies.set('auth', 'true',{ expires: 2,sameSite: 'strict'})
                    Cookies.set('role', this.user.role, { expires: 2,sameSite: 'strict'})
                }
            } catch (error) {
                if (error.response && error.response.status === 401){
                    router.push({name: 'login'})
                } else {
                    this.storeError(error)
                    router.push({name: 'error'})
                }
            }
            this.isLoaded = false
            App.preloader.classList.add('d-none')
        },

         sleep(num) {
            setTimeout(()=>{
                this.isLoaded = false
                App.preloader.classList.add('d-none')
            },num)
        },


        async loginUser() {
            try {
                const response = await api.post('/api/user/login', this.user)
                if (response.status === 200 && response.data.data) {
                    this.user = response.data.data
                    this.isAuth = true
                    this.errors.email = null
                    this.errors.password = null
                    Cookies.set('auth', 'true',{ expires: 2,sameSite: 'strict'})
                    Cookies.set('role', this.user.role, { expires: 2,sameSite: 'strict'})
                    router.push({name: 'home'})
                }
            } catch (error){
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors
                } else {
                    this.storeError(error)
                    router.push({name: 'error'})
                }
            }
        }

    }
})
