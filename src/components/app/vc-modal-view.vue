<template>
    <div class="modal-backdrop">
    <div class="modal">
      <header class="modal-header">
        <slot name="header">
          {{ Title }} - {{CURRENT_OBJECT.Title}} <br> за {{ choiseDate }}

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
          <div v-for="f in this.fotos" v-bind:key="f.index">
            <div class="card">
              <div class="card-body">
                <img :src="f"
                  :height="100"
                  :width="150"
                >
              </div>
            </div>
         </div>
        </slot>
       </section>
       <footer class="modal-footer">
        <slot name="footer">
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
  props: {
    Title: {
      type: String,
      default: 'Modal'
    },
    fotos: {
      type: Array
    },
    chDate: {
      type: String,
      default: ''
    }
  },
  name: 'modalView',
  data: () => ({
    Fotos: null,
    choiseDate: null
  }),
  computed: {
    ...mapGetters([
      'CURRENT_OBJECT'
    ])
  },
  methods: {
    close () {
      this.$emit('closeView')
    }
  },
  updated () {
    this.Fotos = this.fotos
    this.choiseDate = this.chDate
    // console.log(this.isInputMultiple)
  }
}
</script>
