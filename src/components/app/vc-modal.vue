<template>
  <div class="modal-backdrop">
    <div class="modal">
      <header class="modal-header">
        <slot name="header">
          {{ Title }} - {{CURRENT_OBJECT.Title}}

          <button
            type="button"
            class="btn-close"
            @click="close"
          >
            x
          </button>
        </slot>
      </header>
      <section class="modal-body">
        <slot name="body">
          <label for="chois_Date">Дата документа</label>
          <input
            required
            type="date"
            name="chois_Date"
            ref="chois_Date"
            class="form-control mt-1"
            v-model="choiseDate"
          >
          <label for="pictures" class="form-label">Документ</label>
          <input
            required
            type="file"
            accept="image/*,image/jpeg,image/jpg"
            id="file"
            ref="file"
            name="pictures"
            class="form-control mt-1"
            v-on:change="chois_File"
            :multiple=isInputMultiple
            >
        </slot>
       </section>
       <footer class="modal-footer">
        <slot name="footer">
            <button
              @click="sendData"
              type="button"
              class="btn btn-primary"
              id="btnSend"
              :disabled="isButtonDisabled"
            >
              Добавить
            </button>

            <button
              type="button"
              class="btn-green"
              @click="close"
            >
              Закрыть!
          </button>
        </slot>
      </footer>
    </div>
  </div>
</template>

<style>
  .modal-backdrop {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: rgba(0, 0, 0, 0.3);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
  }

  .modal {
    background: #FFFFFF;
    box-shadow: 2px 2px 20px 1px;
    overflow-x: auto;
    display: flex;
    flex-direction: column;
  }

  .modal-header,
  .modal-footer {
    padding: 15px;
    display: flex;
  }

  .modal-header {
    border-bottom: 1px solid #eeeeee;
    color: #4AAE9B;
    justify-content: space-between;
  }

  .modal-footer {
    border-top: 1px solid #eeeeee;
    justify-content: flex-end;
  }

  .modal-body {
    position: relative;
    padding: 20px 10px;
  }

  .btn-close {
    border: none;
    font-size: 20px;
    padding: 20px;
    cursor: pointer;
    font-weight: bold;
    color: #4AAE9B;
    background: transparent;
  }

  .btn-green {
    color: white;
    background: #4AAE9B;
    border: 1px solid #4AAE9B;
    border-radius: 2px;
  }
</style>

<script>
import { mapGetters } from 'vuex'

export default {
  el: 'button',
  props: {
    Title: {
      type: String,
      default: 'Modal'
    },
    chDate: {
      type: String,
      default: ''
    },
    isInputMultiple: {
      type: Boolean,
      default: false
    }
  },
  name: 'modal',
  data: () => ({
    file: null,
    files: null,
    choiseDate: null,
    isButtonDisabled: true
  }),
  computed: {
    ...mapGetters([
      'CURRENT_OBJECT'
    ])
  },
  methods: {
    close () {
      this.file = null
      this.choiseDate = null
      this.$refs.chois_Date.value = null
      this.$refs.file.value = null
      this.isButtonDisabled = true
      this.$emit('close')
    },
    chois_File () {
      this.file = this.$refs.file.files[0]
      this.files = this.$refs.file.files
      if ((this.file !== null) && (this.choiseDate !== null)) {
        this.isButtonDisabled = false
      }
    },
    sendData () {
      this.$emit('choiseDate', this.choiseDate)
      this.$emit('choiseFile', this.file, this.files)
      this.$emit('choiseFiles', this.files)
      this.$emit('sendData')
      this.close()
    }
  },
  updated () {
    const test = new Date().format('YYYY-MM-DD')
    if (this.chDate === '') {
      this.choiseDate = test
    } else {
      // console.log(test)
      this.choiseDate = this.chDate
    // console.log(this.isInputMultiple)
    }
  }
}
</script>
