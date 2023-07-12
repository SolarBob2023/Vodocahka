<script>
import { defineComponent } from 'vue'
import api from '@/api'
import { useUserStore } from '@/stores/User'
import { mapStores } from 'pinia'

export default defineComponent({
  name: 'App',
  computed: {
    ...mapStores(useUserStore)
  },
  mounted() {
    api
      .get('/api/sanctum/csrf-cookie')
      .then((response) => {
        console.log(response)
      })
      .catch((error) => {
        console.log(error)
      })
  },
  methods: {
    logout() {
      api
        .get('/api/user/logout')
        .then((response) => {
          this.userStore.$reset()
          console.log(response)
        })
        .catch((error) => {
          console.log(error)
        })
    }
  }
})
</script>

<template>
  <div class="container d-flex flex-column">
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="container-fluid">
        <RouterLink class="navbar-brand" :to="{ name: 'home' }">Водокачка +</RouterLink>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <RouterLink v-if="!userStore.isAuth" class="nav-link" :to="{ name: 'login' }"
                >Авторизация</RouterLink
              >
            </li>
            <li class="nav-item">
              <RouterLink v-if="!userStore.isAuth" class="nav-link" :to="{ name: 'register' }"
                >Регистрация</RouterLink
              >
            </li>
            <li class="nav-item" v-if="userStore.isAuth">
              <a class="nav-link" @click.prevent="logout" href="#">Выйти</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="row col-8 align-self-center justify-content-center col">
      <RouterView></RouterView>
    </div>
  </div>
</template>

<style scoped></style>
