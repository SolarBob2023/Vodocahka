import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', {
  state: () => ({
    user: {
      name: null,
      email: null,
      surname: null,
      role: null,
      patronymic: null
    },

    isAuth: false
  }),
  getters: {},
  actions: {
    store(user) {
      this.user = user
    },
    authUser() {
      this.isAuth = true
    }
  }
})
