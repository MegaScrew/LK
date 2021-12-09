<template>
  <div>
    <div class="page-title">
      <router-link to="/" class="black-text">
        <i class="material-icons">home</i>
      </router-link>
      <h3>Справка о фермерском хозяйстве по магазину - {{CURRENT_OBJECT.Title}}</h3>
      <button class="btn waves-effect waves-light btn-small" @click="btnRefresh">
        <i class="material-icons">refresh</i>
      </button>
    </div>
    <div>
      <i class="material-icons">pets</i>
      <a href="#" class="black-text" @click.prevent="openModal()">
        Загрузить справку о фермерском хозяйстве
      </a>
    </div>
    <h6>Прикреплённые Справки о фермерском хозяйстве</h6>
    <div v-for="c in CERTIFICATE" v-bind:key="c.url">
      <div class="card">
        <div class="card-body">
          <img :src="c.url"
            :height="100"
            :width="150"
          >
        </div>
      </div>
    </div>
    <modal
      v-show="isOpen"
      @choiseDate="funcChoisDate($event)"
      @choiseFile="funcFile($event)"
      @sendData="sendData"
      @close="closeModal"
      Title='Справка о наличии фермерского хозяйства'
    />
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import Modal from '@/components/app/vc-modal'

export default {
  name: 'Farm_certificate',
  data: () => ({
    isOpen: false,
    choisDate: 0,
    file: '',
    varDate: ''
  }),
  computed: {
    ...mapGetters([
      'CURRENT_OBJECT',
      'CERTIFICATE'
    ])
  },
  methods: {
    ...mapActions([
      'GET_CERTIFICATE',
      'SET_CERTIFICATE'
    ]),
    set_certificate (dealId, fileDate, file1) {
      const data = new FormData()
      data.append('file', file1, file1.name)
      data.append('fileDate', fileDate)
      data.append('dealId', dealId)
      data.append('Steps', '7')
      return this.SET_CERTIFICATE(data)
    },
    sendData () {
      if (this.isOpen) {
        if (this.set_certificate(this.CURRENT_OBJECT.Deal_id, this.choisDate, this.file)) {
          this.choisDate = null
          this.file = null
        }
      }
    },
    openModal () {
      this.isOpen = !this.isOpen
    },
    closeModal () {
      this.isOpen = !this.isOpen
    },
    funcChoisDate (tempVar) {
      this.choisDate = tempVar
    },
    funcFile (tempVar) {
      this.file = tempVar
    },
    sleep (ms) {
      ms += new Date().getTime()
      while (new Date() < ms) {}
    },
    btnRefresh () {
      this.GET_CERTIFICATE(this.CURRENT_OBJECT.Deal_id)
    }
  },
  components: {
    Modal
  },
  actions: {
  },
  mounted () {
    if (this.CURRENT_OBJECT.Deal_id === 0) {
      this.$router.push('/')
    } else {
      this.GET_CERTIFICATE(this.CURRENT_OBJECT.Deal_id)
    }
  }
}
</script>
