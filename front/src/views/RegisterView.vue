<script>
import {defineComponent} from 'vue'
import {useUserStore} from '@/stores/User'
import {mapStores} from 'pinia'

export default defineComponent({
  name: 'RegisterView',

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
      <label class="form-label">Ваше имя</label>
      <input v-model="userStore.user.name" type="text" class="form-control" placeholder="Иванов" />
      <div v-if="userStore.errors.name" class="text-danger">{{ userStore.errors.name[0] }}</div>
    </div>
    <div class="mb-3">
      <label class="form-label">Ваша фамилия</label>
      <input v-model="userStore.user.surname" type="text" class="form-control" placeholder="Иван" />
      <div v-if="userStore.errors.surname" class="text-danger">{{ userStore.errors.surname[0] }}</div>
    </div>
    <div class="mb-3">
      <label class="form-label">Ваше отчетсво</label>
      <input v-model="userStore.user.patronymic" type="text" class="form-control" placeholder="Иванович" />
      <div v-if="userStore.errors.patronymic" class="text-danger">{{ userStore.errors.patronymic[0] }}</div>
    </div>
    <div class="mb-3">
      <label class="form-label">Пароль</label>
      <input v-model="userStore.user.password" type="password" class="form-control" placeholder="password" />
      <div v-if="userStore.errors.password" class="text-danger">{{ userStore.errors.password[0] }}</div>
    </div>
    <div class="mb-3">
      <label class="form-label">Повторите пароль</label>
      <input
        v-model="userStore.user.password_confirmation"
        type="password"
        class="form-control"
        placeholder="password"
      />
      <div v-if="userStore.errors.password_confirmation" class="text-danger">
        {{ userStore.errors.password_confirmation[0] }}
      </div>
    </div>
    <input type="submit" @click.prevent="userStore.register" class="btn btn-primary" value="Регистрация" />
  </div>
</template>

<style scoped></style>
