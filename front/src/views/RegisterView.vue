<script>
import { defineComponent } from 'vue'
import api from '@/api'
import { useUserStore } from '@/stores/User'
import { mapStores } from 'pinia'

export default defineComponent({
  name: 'RegisterView',

  computed: {
    ...mapStores(useUserStore)
  },

  data() {
    return {
      user: {
        name: null,
        email: null,
        surname: null,
        password: null,
        password_confirmation: null,
        patronymic: null
      },
      errors: {
        email: null,
        name: null,
        surname: null,
        patronymic: null,
        password: null,
        password_confirmation: null
      }
    }
  },

  methods: {
    storeUser() {
      console.log(this.user)
      api
        .post('/api/user', this.user)
        .then((response) => {
          this.errors.email = null
          this.errors.name = null
          this.errors.surname = null
          this.errors.patronymic = null
          this.errors.password = null
          this.errors.password_confirmation = null
          this.userStore.store(response.data.data)
          this.userStore.authUser()
          this.$router.push({ name: 'home' })
          console.log(response)
        })
        .catch((error) => {
          // ошибка валидации
          if (error.response.status === 422) {
            this.errors = error.response.data.errors
            // console.log(error.response.data.errors);
          } else console.log(error)
        })
    }
  }
})
</script>

<template>
  <div class="row">
    <div class="mb-3">
      <label class="form-label">Ваш email</label>
      <input v-model="user.email" type="email" class="form-control" placeholder="user@maail.ru" />
      <div v-if="errors.email" class="text-danger">{{ errors.email[0] }}</div>
    </div>
    <div class="mb-3">
      <label class="form-label">Ваше имя</label>
      <input v-model="user.name" type="text" class="form-control" placeholder="Иванов" />
      <div v-if="errors.name" class="text-danger">{{ errors.name[0] }}</div>
    </div>
    <div class="mb-3">
      <label class="form-label">Ваша фамилия</label>
      <input v-model="user.surname" type="text" class="form-control" placeholder="Иван" />
      <div v-if="errors.surname" class="text-danger">{{ errors.surname[0] }}</div>
    </div>
    <div class="mb-3">
      <label class="form-label">Ваше отчетсво</label>
      <input v-model="user.patronymic" type="text" class="form-control" placeholder="Иванович" />
      <div v-if="errors.patronymic" class="text-danger">{{ errors.patronymic[0] }}</div>
    </div>
    <div class="mb-3">
      <label class="form-label">Пароль</label>
      <input v-model="user.password" type="password" class="form-control" placeholder="password" />
      <div v-if="errors.password" class="text-danger">{{ errors.password[0] }}</div>
    </div>
    <div class="mb-3">
      <label class="form-label">Повторите пароль</label>
      <input
        v-model="user.password_confirmation"
        type="password"
        class="form-control"
        placeholder="password"
      />
      <div v-if="errors.password_confirmation" class="text-danger">
        {{ errors.password_confirmation[0] }}
      </div>
    </div>
    <input type="submit" @click.prevent="storeUser" class="btn btn-primary" value="Регистрация" />
  </div>
</template>

<style scoped></style>
