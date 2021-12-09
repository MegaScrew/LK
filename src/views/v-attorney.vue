<template>
    <div>
        <div class="page-title">
            <router-link to="/" class="black-text">
              <i class="material-icons">home</i>
            </router-link>
            <h3>Список доверенностей по магазину {{CURRENT_OBJECT.Title}}</h3>
        </div>
        <div v-for="a in ATTORNEY" v-bind:key="a.Id">
          {{a.Title}} Скачать: <a :href=a.pdfUrl target="_blank">pdf</a> или <a :href=a.imageUrl target="_blank">image</a>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
export default {
  name: 'Attorney',
  computed: {
    ...mapGetters([
      'CURRENT_OBJECT',
      'ATTORNEY'
    ])
  },
  methods: {
    ...mapActions([
      'GET_ATTORNEY',
      'CLEAR_ATTORNEY'
    ])
  },
  actions: {
  },
  mounted () {
    this.CLEAR_ATTORNEY()
    if (this.CURRENT_OBJECT.Deal_id === 0) {
      this.$router.push('/')
    } else {
      this.GET_ATTORNEY(this.CURRENT_OBJECT.Deal_id)
    }
  }
}
</script>
