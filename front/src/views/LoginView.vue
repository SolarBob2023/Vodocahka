<script>
import {defineComponent} from 'vue'

export default defineComponent({
  name: "LoginView"
})
</script>

<template>
  <div class="row">
    <div class="mb-3">
      <label class="form-label">Ваш email</label>
      <input type="email" class="form-control" placeholder="user@maail.ru">
    </div>
    <div class="mb-3">
      <label class="form-label">Пароль</label>
      <input type="password" class="form-control" placeholder="password">
    </div>
    <input type="submit" class="btn btn-primary" value="Войти">
  </div>
</template>

<style scoped>

</style>