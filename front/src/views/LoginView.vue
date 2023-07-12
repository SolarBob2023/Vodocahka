<script>
import { defineComponent } from 'vue'
import api from '@/api'
import { mapStores } from 'pinia'
import { useUserStore } from '@/stores/User'

export default defineComponent({
  name: 'LoginView',

  computed: {
    ...mapStores(useUserStore)
  },

  data() {
    return {
      user: {
        email: null,
        password: null
      },
      errors: {
        name: null,
        password: null
      }
    }
  },

  methods: {
    loginUser() {
      api
        .post('/api/user/login', this.user)
        .then((response) => {
          this.userStore.store(response.data.data)
          this.userStore.authUser()
          this.errors.email = null
          this.errors.password = null
          this.$router.push({ name: 'home' })
          console.log(response)
        })
        .catch((error) => {
          if (error.response.status === 422) {
            this.errors = error.response.data.errors
            // console.log(error.response.data.errors);
          }
          console.log(error)
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
      <label class="form-label">Пароль</label>
      <input v-model="user.password" type="password" class="form-control" placeholder="password" />
      <div v-if="errors.password" class="text-danger">{{ errors.password[0] }}</div>
    </div>
    <input @click.prevent="loginUser" type="submit" class="btn btn-primary" value="Войти" />
  </div>
</template>

<style scoped></style>
