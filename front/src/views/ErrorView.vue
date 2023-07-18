<script>
import {defineComponent} from 'vue'
import {mapStores} from 'pinia'
import {useErrorStore} from "@/stores/Error";

export default defineComponent({
  name: 'ErrorView',
  data() {
    return {
      status : null,
      message: null,
    }
  },
  computed: {
    ...mapStores(useErrorStore)
  },

  methods: {
  },
  mounted() {
    const error = this.errorStore.getError;
    console.log(error);
    if (error)  {
      if (error.response && error.response.data) {
        //Не авторизован
        this.message = error.response.data.errors
        this.status = error.response.status
        // console.log(error.response.data);
        // console.log(error.response.status);
        // console.log(error.response.headers);
      } else if (error.request && error.request.data) {
        // console.log(error.request);
      } else {
        this.message = error.message
        // console.log('Error', error.message);
      }
    }
  },

  unmounted(){
    this.errorStore.$reset();
  }

})
</script>

<template>
    <h1 class="d-flex justify-content-center">Ошибка</h1>
    <h2 v-if="status" class="d-flex justify-content-center text-danger">Статус: {{ status }}</h2>
    <h4 v-if="message" class="d-flex justify-content-center">{{ message }}</h4>
</template>

<style scoped></style>
