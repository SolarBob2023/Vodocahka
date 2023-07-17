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
  name: 'BillView',

  components: {VueDatePicker},
  data() {
    return {
      month: {
        month: new Date().getMonth() - 1,
        year: new Date().getFullYear()
      },
      bills: {
        data: null,
        links: null,
      },
    };
  },


  computed: {
    ru() {
      return ru
    },


    maxPeriod(){
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
      return this.period<=this.maxPeriod && this.period>0
    }

  },

  methods: {
    addMonths,
    format,
    ...mapActions(useErrorStore,{ storeError: 'storeError' }),
    async getBills(page = 1) {
      try {
        const response = await api.get(`/api/admin/bills/${this.period}?page=${page}`)
        if (response.status === 200 && response.data) {
          this.bills.data = response.data.data
          this.bills.links = response.data.meta.links
          // Для пагинации
          this.bills.links.forEach((item) => {
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
    }
  }


})
</script>

<template>
  <div class="row">
    <div class="mt-2">Выставленные счета</div>
    <VueDatePicker class="mt-2" v-model="month" month-picker auto-apply/>
    <a @click.prevent="getBills()"
       :class="'btn btn-primary mt-2 ' + (isValidPeriod ? '' : 'disabled')"
       href="#">
      Показать
    </a>
    <div v-if="bills.data && bills.data.length===0" class="mt-2">
      <p class="text-danger">За выбранный период не выставлены счета</p>
    </div>
    <div v-if="!isValidPeriod" class="mt-2">
      <p class="text-danger">Период должен быть позднее января 2022 года и раньше
        {{format(addMonths(new Date(), 1), 'MMMM', {locale: ru})}} текущего года</p>
    </div>
    <div v-if="bills.data && bills.data.length>0" class="mt-2">
      <table class="table">
        <thead>
        <tr>
          <th scope="col">ФИО дачника</th>
          <th scope="col">Сумма</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="item in bills.data" :key="item.id">
          <th>{{ item['resident']['fio'] }}</th>
          <td>{{ item['bill'] }}</td>
        </tr>
        </tbody>
      </table>
      <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
          <li
              v-for="link in bills.links"
              :key="link.label"
              :class="'page-item ' + (link.active ? 'active ' : '') + (link.url ? '' : 'disabled')">
            <a @click.prevent="getBills(link.page)"
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
