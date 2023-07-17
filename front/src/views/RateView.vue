<script>
import {defineComponent} from 'vue'
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import {differenceInMonths, format, addMonths} from 'date-fns'
import api from "@/api";
import router from "@/router";
import {mapActions} from "pinia";
import {useErrorStore} from "@/stores/Error";
import {ru} from "date-fns/locale";

export default defineComponent({
  name: 'RateView',

  components: {VueDatePicker},
  data() {
    return {
      month: {
        month: new Date().getMonth() + 1,
        year: new Date().getFullYear()
      },
      rates: {
        data: null,
        links: null,
      },
      errors : {
        price: null,
        period: null,
      },
      price: null,
      addingRate: false,
    };
  },


  computed: {
    ru() {
      return ru
    },

    minPeriod(){
      const beginDate = new Date(2022, 1, 1);
      const selectedDate = new Date(new Date().getFullYear(), new Date().getMonth(), 1);
      return differenceInMonths(selectedDate, beginDate) + 2
    },

    period() {
      const beginDate = new Date(2022, 1, 1);
      const selectedDate = new Date(this.month.year, this.month.month, 1);
      return differenceInMonths(selectedDate, beginDate) + 2
    },

    isValidPeriod(){
      return this.period > this.minPeriod
    },

  },
  mounted() {
    this.getRates()
  },
  methods: {
    addMonths,
    format,
    ...mapActions(useErrorStore,{ storeError: 'storeError' }),
    changeAddingRate(){
      this.addingRate = !this.addingRate;
    },
    async getRates(page = 1) {
      try {
        const response = await api.get(`/api/admin/rates?page=${page}`)
        if (response.status === 200 && response.data) {
          this.rates.data = response.data.data
          this.rates.links = response.data.meta.links
          // Для пагинации
          this.rates.links.forEach((item) => {
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

    async addRate(){
      try {
        const response = await api.post(`/api/admin/rates`,
            { price : this.price, period: this.period }
        )
        if (response.status === 200 || response.status === 201) {
          await this.getRates()
          this.errors.period = null
          this.errors.price = null
          this.changeAddingRate()
        }
        // console.log(response);
      } catch (error) {
        if (error.response && error.response.status === 422) {
          this.errors = error.response.data.errors
        } else {
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

<!--    Добавление новго тарифа-->
    <div v-if="addingRate">
      <div class="form-label mt-2">Период</div>
      <VueDatePicker clas="mt-2" v-model="month" month-picker auto-apply/>
      <div v-if="errors.period" class="text-danger mt-2">{{ errors.period[0] }}</div>
      <div v-if="!isValidPeriod" class="text-danger mt-2">Разрешено изменть цену на тариф только для будущих периодов</div>
      <div class="form-label mt-2">Цена</div>
      <input type="number" v-model="price" class="form-control mt-2" step="0.01"  placeholder="Цена">
      <div v-if="errors.price" class="text-danger mt-2">{{ errors.price[0] }}</div>
      <input type="submit"
             @click.prevent="addRate"
             :class="'form-control btn btn-primary mt-2 ' + (isValidPeriod ? '' : 'disabled')"
             value="Изменить тариф">
    </div>

<!--    Список тарифов-->
    <div v-if="rates.data && rates.data.length>0">
      <table class="table">
        <thead>
        <tr>
          <th scope="col">Год</th>
          <th scope="col">Месяц</th>
          <th scope="col">Тариф</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="item in rates.data" :key="item.id">
          <th>{{ item['year']}}</th>
          <td>{{ item['month'] }}</td>
          <td>{{ item['price'] }}</td>
        </tr>
        </tbody>
      </table>
      <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
          <li
              v-for="link in rates.links"
              :key="link.label"
              :class="'page-item ' + (link.active ? 'active ' : '') + (link.url ? '' : 'disabled')">
            <a @click.prevent="getRates(link.page)"
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
