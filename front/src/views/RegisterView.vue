<script>
import {defineComponent} from 'vue'

export default defineComponent({
  name: "RegisterView"
})
</script>

<template>
  <div class="row">
    <div class="mb-3">
      <label class="form-label">Ваш email</label>
      <input type="email" class="form-control" placeholder="user@maail.ru">
    </div>
    <div class="mb-3">
      <label class="form-label">Ваше имя</label>
      <input type="email" class="form-control" placeholder="Иванов">
    </div>
    <div class="mb-3">
      <label class="form-label">Ваша фамилия</label>
      <input type="email" class="form-control" placeholder="Иван">
    </div>
    <div class="mb-3">
      <label class="form-label">Ваше отчетсво</label>
      <input type="email" class="form-control" placeholder="Иванович">
    </div>
    <div class="mb-3">
      <label class="form-label">Пароль</label>
      <input type="password" class="form-control" placeholder="password">
    </div>
    <div class="mb-3">
      <label class="form-label">Повторите пароль</label>
      <input type="password" class="form-control" placeholder="password">
    </div>
    <input type="submit" class="btn btn-primary" value="Войти">
  </div>
</template>

<style scoped>

</style>