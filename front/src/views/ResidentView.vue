<script>
import {defineComponent} from 'vue'
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import {differenceInMonths} from 'date-fns'
import api from "@/api";
import router from "@/router";
import {mapActions} from "pinia";
import {useErrorStore} from "@/stores/Error";

export default defineComponent({
  name: 'ResidentView',

  components: {VueDatePicker},
  data() {
    return {
      residents: {
        data: null,
        links: null,
      },
      resident : {
        fio: null,
        area: null,
        start_date: new Date(),
      },
      errors : {
        fio: null,
        area: null,
        start_date: null,
        message : null
      },
      addingRate: false,
    };
  },


  computed: {
    minPeriod(){
      const beginDate = new Date(2022, 1, 1);
      const selectedDate = new Date(new Date().getFullYear(), new Date().getMonth() - 1, 1);
      return differenceInMonths(selectedDate, beginDate) + 2
    },

    period() {
      const beginDate = new Date(2022, 1, 1);
      const selectedDate = new Date(this.resident.start_date.getFullYear(), this.resident.start_date.getMonth(), 1);
      return differenceInMonths(selectedDate, beginDate) + 2
    },

    isValidPeriod(){
      return this.period >= this.minPeriod
    },

  },
  mounted() {
    this.getResidents()
  },
  methods: {
    ...mapActions(useErrorStore,{ storeError: 'storeError' }),
    changeAddingRate(){
      this.addingRate = !this.addingRate;
    },

    clearInputs(){
      this.resident.fio = null
      this.resident.area = null
      this.resident.start_date = new Date()
      this.errors.fio = null
      this.errors.area = null
      this.errors.start_date = null
      this.errors.message = null
    },
    async getResidents(page = 1) {
      try {
        const response = await api.get(`/api/admin/residents?page=${page}`)
        if (response.status === 200 && response.data) {
          this.residents.data = response.data.data
          this.residents.links = response.data.meta.links
          // Для пагинации
          this.residents.links.forEach((item) => {
                if (item.url) {
                  const url = new URL(item.url);
                  item['page'] = url.searchParams.get('page');
                } else {
                  item['page'] = null
                }
              }
          )
        }
        // console.log(response);
      } catch (error) {
        this.storeError(error)
        router.push({name: 'error'})
        // console.log(error);
      }
    },

    async addResident(){
      try {
        const response = await api.post(`/api/admin/residents`,
            {
              fio: this.resident.fio,
              area : this.resident.area,
              start_date : this.resident.start_date
            }
        )
        if (response.status === 201) {
          await this.getResidents()
          this.clearInputs();
          this.changeAddingRate()
        }
        // console.log(response);
      } catch (error) {
        if (error.response && error.response.status === 422) {
          this.errors = error.response.data.errors
        } else {
          this.clearInputs();
          this.changeAddingRate()
          this.storeError(error)
          router.push({name: 'error'})
        }
      }
    }
  }


})
</script>

<template>
  <div class="row">
    <div class="row d-flex justify-content-between">
      <h2 class="col-2">Список</h2>
      <button v-if="!addingRate" @click.prevent="changeAddingRate" class="btn btn-primary col-2">+</button>
      <button v-if="addingRate" @click.prevent="changeAddingRate" class="btn btn-primary col-2">X</button>
    </div>

<!--    Добавление новго дачника-->
    <div v-if="addingRate">
      <div class="form-label mt-2">Дата подключения</div>
      <VueDatePicker class="mt-2" v-model="resident.start_date" auto-apply text-input />
      <div v-if="errors.start_date" class="text-danger mt-2">{{ errors.start_date[0] }}</div>
      <div v-if="!isValidPeriod" class="text-danger mt-2">Нельзя добавить дачника в прошедший период</div>
      <div class="form-label mt-2">Площадь</div>
      <input type="number" v-model="resident.area" class="form-control mt-2" step="0.01"  placeholder="Площадь">
      <div v-if="errors.area" class="text-danger mt-2">{{ errors.area[0] }}</div>
      <div class="form-label mt-2">ФИО</div>
      <input type="text" v-model="resident.fio" class="form-control mt-2"  placeholder="ФИО">
      <div v-if="errors.fio" class="text-danger mt-2">{{ errors.fio[0] }}</div>
      <div v-if="errors.message" class="text-danger mt-2">{{ errors.message[0] }}</div>
      <input type="submit"
             @click.prevent="addResident"
             :class="'form-control btn btn-primary mt-2 ' + (isValidPeriod ? '' : 'disabled')"
             value="Добавить дачника">
    </div>

<!--    Список дачников-->
    <div v-if="residents.data && residents.data.length>0">
      <table class="table">
        <thead>
        <tr>
          <th scope="col">ФИО</th>
          <th scope="col">Площадь</th>
          <th scope="col">Дата подключения</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="item in residents.data" :key="item.id">
          <th>{{ item['fio']}}</th>
          <td>{{ item['area'] }}</td>
          <td>{{ item['start_date'] }}</td>
        </tr>
        </tbody>
      </table>
      <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
          <li
              v-for="link in residents.links"
              :key="link.label"
              :class="'page-item ' + (link.active ? 'active ' : '') + (link.url ? '' : 'disabled')">
            <a @click.prevent="getResidents(link.page)"
               class="page-link" href="#"
               v-html="link.label"
            ></a>
          </li>

        </ul>
      </nav>
    </div>

  </div>
</template>

<style scoped></style>
