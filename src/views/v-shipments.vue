<template>
    <div>
        <div class="page-title">
            <router-link to="/" class="black-text">
              <i class="material-icons">home</i>
            </router-link>
            <h3>Отчет по отгрузкам за последний месяц по магазину {{CURRENT_OBJECT.Title}}</h3>
        </div>
        <h3>{{get_month1()}}</h3>
        <div v-for="s in SHIPMENTS1" v-bind:key="s.Id">
          {{s.Date}} {{s.Nomenclature}} {{s.Weight}}
        </div>
        <p>Итого: {{summShipments1}} кг.</p>
        <h3>{{get_month2()}}</h3>
        <div v-for="s in SHIPMENTS2" v-bind:key="s.Id">
          {{s.Date}} {{s.Nomenclature}} {{s.Weight}}
        </div>
        <p>Итого: {{summShipments2}} кг.</p>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
export default {
  name: 'Shipments',
  computed: {
    ...mapGetters([
      'CURRENT_OBJECT',
      'SHIPMENTS1',
      'SHIPMENTS2'
    ]),
    summShipments1 () {
      let result = []
      if (this.SHIPMENTS1.length) {
        for (const item of this.SHIPMENTS1) {
          result.push(Number(item.Weight))
        }
        result = result.reduce(function (sum, el) {
          return sum + el
        })
        return result.toFixed(3)
      } else {
        return 0
      }
    },
    summShipments2 () {
      let result = []
      if (this.SHIPMENTS2.length) {
        for (const item of this.SHIPMENTS2) {
          result.push(Number(item.Weight))
        }
        result = result.reduce(function (sum, el) {
          return sum + el
        })
        return result.toFixed(3)
      } else {
        return 0
      }
    }
  },
  methods: {
    ...mapActions([
      'GET_SHIPMENTS',
      'CLEAR_SHIPMENTS'
    ]),
    get_month1 () {
      // const dataShipment = '2021-10-29'
      let month = new Date()
      if (this.SHIPMENTS1.length > 0) {
        month = new Date(this.SHIPMENTS1[0].Date)
      }
      // const month2 = month.getMonth()
      var options = {
        // era: 'long',
        // year: 'numeric',
        month: 'long'
        // day: 'numeric',
        // weekday: 'long',
        // timezone: 'UTC',
        // hour: 'numeric',
        // minute: 'numeric',
        // second: 'numeric'
      }
      return month.toLocaleString('ru', options)
    },
    get_month2 () {
      // const dataShipment = '2021-10-29'
      let month = new Date()
      if (this.SHIPMENTS2.length > 0) {
        month = new Date(this.SHIPMENTS2[0].Date)
      }
      // const month2 = month.getMonth()
      var options = {
        // era: 'long',
        // year: 'numeric',
        month: 'long'
        // day: 'numeric',
        // weekday: 'long',
        // timezone: 'UTC',
        // hour: 'numeric',
        // minute: 'numeric',
        // second: 'numeric'
      }
      return month.toLocaleString('ru', options)
    }
  },
  actions: {
  },
  mounted () {
    this.CLEAR_SHIPMENTS()
    if (this.CURRENT_OBJECT.InnerNumber === 0) {
      this.$router.push('/')
    } else {
      this.GET_SHIPMENTS(this.CURRENT_OBJECT.InnerNumber)
    }
  }
}
</script>
