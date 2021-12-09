<template>
    <div>
      <div class="page-title">
        <router-link to="/" class="black-text">
          <i class="material-icons">home</i>
        </router-link>
        <h3>Фото отчеты - {{CURRENT_OBJECT.Title}}</h3>
        <button class="btn waves-effect waves-light btn-small" @click="btnRefresh">
          <i class="material-icons">refresh</i>
        </button>
      </div>
    <div>
      <i class="material-icons">add_a_photo</i>
      <a href="#" class="black-text" @click.prevent="openModal()">
        Добавить фото отчеты
      </a>
    </div>
    <h6>Прикреплённые фото отчеты</h6>
    <div>
      <modal
        v-show="isOpen"
        @choiseDate="funcChoisDate($event)"
        @choiseFile="funcFile($event)"
        @choiseFiles="funcFiles($event)"
        @sendData="sendData"
        @close="closeModal"
        Title='Добавить фото отчеты по магазину'
        :chDate="varDate"
        :isInputMultiple="true"
      />
    </div>
    <div>
      <modalView
        v-show="isOpenView"
        Title='Просмотр Фото отчета по магазину'
        :fotos="fotos"
        :chDate="varDate"
        @closeView="closeModalView"
      />
    </div>
    <div style="width: 270px;height: 300px">
      <vue-cal
        class="vuecal--blue-theme"
        hide-view-selector
        active-view="month"
        events-count-on-year-view
        xsmall
        locale="ru"
        :disable-views="['years', 'year', 'week']"
        :clickToNavigate="false"
        :dblclickToNavigate="false"
        :time="false"
        :events="events"
        @cell-click="cellClick"
        @view-change="viewChange($event)"
      >
      </vue-cal>
    </div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import Modal from '@/components/app/vc-modal'
import ModalView from '@/components/app/vc-modal-view'
import VueCal from 'vue-cal'
import 'vue-cal/dist/i18n/ru.js'
import 'vue-cal/dist/vuecal.css'
import axios from 'axios'

export default {
  name: 'Foto',
  data: () => ({
    isOpen: false,
    isOpenView: false,
    choisDate: 0,
    file: '',
    files: '',
    varDate: '',
    events: [
      {
        start: '2021-12-06 09:00',
        end: '2021-12-06 10:00',
        title: 'загаловок',
        class: 'leisure',
        content: 'содержание'
      }
    ],
    fotos: []
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
    sendData () {
      if (this.isOpen) {
        this.setCalEvents()
      }
    },
    openModal () {
      this.isOpen = !this.isOpen
    },
    closeModal () {
      this.isOpen = !this.isOpen
    },
    closeModalView () {
      this.isOpenView = !this.isOpenView
    },
    cellClick (event, e) {
      this.varDate = event.format('YYYY-MM-DD')
      let flag = true
      this.fotos.splice(0)
      for (let index = 0; index < this.events.length; index++) {
        if (this.events[index].start === this.varDate) {
          flag = false
          const tempArr = this.events[index].content.split(', ')
          for (let index = 0; index < tempArr.length; index++) {
            this.fotos.push(tempArr[index])
          }
        }
      }
      if (flag) {
        this.isOpen = !this.isOpen
      } else {
        this.isOpenView = !this.isOpenView
      }
    },
    funcChoisDate (tempVar) {
      this.choisDate = tempVar
    },
    funcFile (tempVar, tempVar2) {
      this.file = tempVar
      this.files = tempVar2
    },
    funcFiles (tempVar) {
      this.files = tempVar
    },
    async getCalEvents () {
      const temp = {
        Steps: '10',
        shopId: this.CURRENT_OBJECT.CompanyId,
        event_date: this.varDate
      }
      const formData = temp
      await axios.get('https://is.smaip.ru/testvue/b24/handler.php', { params: { formData } })
        .then((response) => {
          if (response.data.getCalEvents.length > 0) {
            this.events.splice(0)
            for (let index = 0; index < response.data.getCalEvents.length; index++) {
              let date1 = new Date(response.data.getCalEvents[index][0][0])
              date1 = date1.format('YYYY-MM-DD')
              this.events.push({ start: date1, end: date1, title: 'загаловок2', class: 'leisure', content: response.data.getCalEvents[index][1][0] })
            }
          }
          // console.log(response.data.getCalEvents[0][1])
          return true
        })
        .catch((error) => {
          console.log(error)
          return false
        })
    },
    async setCalEvents () {
      const data = new FormData()
      for (var i = 0; i < this.files.length; i++) {
        const file = this.files[i]
        data.append('files[' + i + ']', file)
      }
      // data.append('files', this.files)
      data.append('fileDate', this.choisDate)
      data.append('shopId', this.CURRENT_OBJECT.CompanyId)
      data.append('InnerNumber', this.CURRENT_OBJECT.InnerNumber)
      data.append('Type', this.CURRENT_OBJECT.Type)
      data.append('Steps', '11')
      const config = { headers: { 'Content-Type': 'multipart/form-data' } }
      await axios.post('https://is.smaip.ru/testvue/b24/handler.php', data, config)
        .then((response) => {
          if (response.data.message === 0) {
            this.getCalEvents()
            // dispatch('GET_CONTRACT', response.data.dealId)
          }
        })
        .catch((error) => {
          console.log(error)
        })
    },
    btnRefresh () {
      this.getCalEvents()
    },
    viewChange ($obj) {
      let date1 = new Date($obj.startDate)
      date1 = date1.format('YYYY-MM-DD')
      this.varDate = date1
      this.events.splice(0)
      this.getCalEvents()
      // console.log($obj)
    }
  },
  components: {
    Modal,
    ModalView,
    'vue-cal': VueCal
  },
  actions: {
  },
  mounted () {
    this.varDate = new Date().format('YYYY-MM-DD')
    if (this.CURRENT_OBJECT.Deal_id === 0) {
      this.$router.push('/')
    } else {
      this.events.splice(0)
      this.getCalEvents()
    }
    // console.log('test')
  }
}
</script>
