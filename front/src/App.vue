<script>

</script>

<template>
  <div class="container d-flex flex-column">
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="container-fluid">
        <RouterLink  class="navbar-brand" :to="{ name : 'home'}">Водокачка +</RouterLink>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <RouterLink class="nav-link" :to="{ name : 'login'}">Авторизация</RouterLink>
            </li>
            <li class="nav-item">
              <RouterLink class="nav-link" :to="{ name : 'register'}">Регистрация</RouterLink>
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

<style scoped>

</style>