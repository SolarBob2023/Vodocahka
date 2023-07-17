<script>
import {defineComponent} from 'vue'
import {mapStores} from 'pinia'
import {useUserStore} from '@/stores/User'

export default defineComponent({
  name: 'LoginView',

  computed: {
    ...mapStores(useUserStore)
  },

  unmounted() {
    this.userStore.resetErrors()
  }
})
</script>

<template>
  <div class="row">
    <div class="mb-3">
      <label class="form-label">Ваш email</label>
      <input v-model="userStore.user.email" type="email" class="form-control" placeholder="user@maail.ru" />
      <div v-if="userStore.errors.email" class="text-danger">{{ userStore.errors.email[0] }}</div>
    </div>
    <div class="mb-3">
      <label class="form-label">Пароль</label>
      <input v-model="userStore.user.password" type="password" class="form-control" placeholder="password" />
      <div v-if="userStore.errors.password" class="text-danger">{{ userStore.errors.password[0] }}</div>
    </div>
    <input @click.prevent="userStore.loginUser" type="submit" class="btn btn-primary" value="Войти" />
  </div>
</template>

<style scoped></style>
