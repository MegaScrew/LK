<template>
  <div>
    <div class="page-title">
      <router-link to="/" class="black-text">
        <i class="material-icons">home</i>
      </router-link>
      <h3>Договор по магазину - {{CURRENT_OBJECT.Title}}</h3>
      <button class="btn waves-effect waves-light btn-small" @click="btnRefresh">
        <i class="material-icons">refresh</i>
      </button>
    </div>
    <div>
      <i class="material-icons">work</i>
      <a href="#" class="black-text" @click.prevent="openModal()">
        Загрузить договор
      </a>
    </div>
    <h6>Прикреплённые договора</h6>
    <div v-for="c in CONTRACT" v-bind:key="c.url">
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
      Title='Договор'
    />
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import Modal from '@/components/app/vc-modal'

export default {
  name: 'Add_contract',
  data: () => ({
    isOpen: false,
    choisDate: 0,
    file: ''
  }),
  computed: {
    ...mapGetters([
      'CURRENT_OBJECT',
      'CONTRACT'
    ])
  },
  methods: {
    ...mapActions([
      'GET_CONTRACT',
      'SET_CONTRACT'
    ]),
    set_contract (dealId, fileDate, file1) {
      const data = new FormData()
      data.append('file', file1, file1.name)
      data.append('fileDate', fileDate)
      data.append('dealId', dealId)
      data.append('Steps', '9')
      return this.SET_CONTRACT(data)
    },
    sendData () {
      if (this.isOpen) {
        if (this.set_contract(this.CURRENT_OBJECT.Deal_id, this.choisDate, this.file)) {
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
      this.GET_CONTRACT(this.CURRENT_OBJECT.Deal_id)
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
      this.GET_CONTRACT(this.CURRENT_OBJECT.Deal_id)
    }
  }
}
</script>
