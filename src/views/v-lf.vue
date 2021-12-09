<template>
    <div>
      <div class="page-title">
          <router-link to="/" class="black-text">
            <i class="material-icons">home</i>
          </router-link>
          <h3>Лист фиксации отсутствия отгрузок</h3>
      </div>
      <div v-for="c in COMPANY" v-bind:key="c.Id">
        <i class="material-icons">description</i>
          <a :href="'https://is.smaip.ru/testvue/b24/ticket/no_shiping.php?id='+c.Deal_id" class="black-text" target="_blank">
            Скачать "Лист фиксации отсутствия отгрузок" по магазину - {{c.Title}}
          </a>
      </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  name: 'Lf',
  computed: {
    ...mapGetters([
      'COMPANY'
    ])
  }
}
</script>
