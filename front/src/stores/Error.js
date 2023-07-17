import {defineStore} from 'pinia'

export const useErrorStore = defineStore('error', {
    state: () => ({
        error : null
    }),
    getters: {
        getError() {
            return this.error
        }
    },
    actions: {
        storeError(error) {
            this.error = error
        }
    }
})
