<script>
import {defineComponent} from 'vue'
import '@vuepic/vue-datepicker/dist/main.css'
import {differenceInMonths} from 'date-fns'
import api from "@/api";
import router from "@/router";
import {mapActions} from "pinia";
import {useErrorStore} from "@/stores/Error";

export default defineComponent({
  name: 'RecordView',

  data() {
    return {
      rates: {
        data: null,
        links: null,
      },
      errors : {
        volume: null,
        period: null,
      },
      volume: null,
      addingRate: false,
    };
  },


  computed: {
    period() {
      const beginDate = new Date(2022, 1, 1);
      const selectedDate = new Date(new Date().getFullYear(), new Date().getMonth() - 1, 1);
      return differenceInMonths(selectedDate, beginDate) + 2
    },

  },
  mounted() {
    this.getRecords()
  },
  methods: {
    ...mapActions(useErrorStore,{ storeError: 'storeError' }),
    changeAddingRate(){
      this.addingRate = !this.addingRate;
    },
    async getRecords(page = 1) {
      try {
        const response = await api.get(`/api/admin/records?page=${page}`)
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
        const response = await api.post(`/api/admin/records`,
            { volume : this.volume, period: this.period }
        )
        if (response.status === 200 || response.status === 201) {
          await this.getRecords()
          this.errors.period = null
          this.errors.volume = null
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

<!--    Добавление новой записи-->
    <div v-if="addingRate">
      <div class="form-label mt-2">Показания счётчика</div>
      <input type="number" v-model="volume" class="form-control mt-2" step="0.01"  placeholder="Цена">
      <div v-if="errors.volume" class="text-danger mt-2">{{ errors.volume[0] }}</div>
      <div v-if="errors.period" class="text-danger mt-2">{{ errors.period[0] }}</div>
      <input type="submit"
             @click.prevent="addRate"
             class="form-control btn btn-primary mt-2"
             value="Добавить показания">
    </div>

<!--    Список тарифов-->
    <div v-if="rates.data && rates.data.length>0">
      <table class="table">
        <thead>
        <tr>
          <th scope="col">Год</th>
          <th scope="col">Месяц</th>
          <th scope="col">Объём</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="item in rates.data" :key="item.id">
          <th>{{ item['year']}}</th>
          <td>{{ item['month'] }}</td>
          <td>{{ item['volume'] }}</td>
        </tr>
        </tbody>
      </table>
      <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
          <li
              v-for="link in rates.links"
              :key="link.label"
              :class="'page-item ' + (link.active ? 'active ' : '') + (link.url ? '' : 'disabled')">
            <a @click.prevent="getRecords(link.page)"
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
