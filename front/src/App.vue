<script>
import {defineComponent} from 'vue'
import {useUserStore} from '@/stores/User'
import {mapStores} from 'pinia'


export default defineComponent({
  name: 'App',
  preloader : document.getElementById("preloader"),
  computed: {
    ...mapStores(useUserStore),

  },
  methods: {
    async test(){
      this.userStore.getUser()
    },
  },
  beforeMount() {
    this.test();
  },

})
</script>

<template>
  <div v-if="!userStore.isLoaded" class="container d-flex flex-column">
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
              >Авторизация
              </RouterLink
              >
            </li>
            <li class="nav-item">
              <RouterLink v-if="!userStore.isAuth" class="nav-link" :to="{ name: 'register' }"
              >Регистрация
              </RouterLink>
            </li>
            <li class="nav-item">
              <RouterLink v-if="userStore.isAuth && userStore.user.role === 2" class="nav-link" :to="{ name: 'bills' }"
              >Счета
              </RouterLink>
            </li>
            <li class="nav-item">
              <RouterLink v-if="userStore.isAuth && userStore.user.role === 2" class="nav-link" :to="{ name: 'rates' }"
              >Тариф
              </RouterLink>
            </li>
            <li class="nav-item">
              <RouterLink v-if="userStore.isAuth && userStore.user.role === 2" class="nav-link" :to="{ name: 'records' }"
              >Счётчик
              </RouterLink>
            </li>
            <li class="nav-item" v-if="userStore.isAuth">
              <a class="nav-link" @click.prevent="userStore.logout()" href="#">Выйти</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="row col-8 align-self-center justify-content-center">
      <RouterView></RouterView>
    </div>
  </div>
</template>

<style scoped></style>
