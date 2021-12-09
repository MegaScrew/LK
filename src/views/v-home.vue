<template>
    <div>
        <div class="page-title">
            <h3>Главная</h3>

            <button class="btn waves-effect waves-light btn-small" @click="btnRefresh">
            <i class="material-icons">refresh</i>
            </button>
        </div>

        <div class="row">
            <div v-for="c in COMPANY" v-bind:key="c.Id">
            <div class="col s12 m6 l12">
                <div class="card light-blue bill-card">
                     <div class="card-content white-text">
                        <h4 class="font-weight-normal mb-3 mr-0" style="width:calc(100% - 26px);">{{c.Title}}</h4>
                        <h2 class="mb-5">{{c.Revenue}} Р</h2>
                        <div v-if="c.PaymentFor == '5958'">
                            <span data-toggle="tooltip" title="" data-original-title="Это ваш остаток на счету. Отрицательно значение баланса является задолженностью.">
                                Баланс
                                <i class="mdi mdi-information-outline"></i>
                            </span>
                            <span style="color:#fe7c96">{{c.Balance}} p.</span>
                        </div>
                        <h6 style="cursor:pointer;"
                            class="card-text mb-0 collapsed"
                            data-toggle="collapse"
                            href="#ui-basic0"
                            aria-expanded="false"
                            aria-controls="ui-basic0">{{c.InnerNumber}}
                            <span>{{c.Type}}</span>
                            / Система оплаты: за
                            <span data-toggle="tooltip" title="" data-original-title="Форма оплаты, в которой Вы платите фиксированную стоимость за месяц. Такая форма оплаты применяется, если вес отгружаемой продукции составляет от 300 до 900 кг за месяц.">
                                <span v-if="c.PaymentFor == 5958"> Кг</span>
                                <span v-else>Месяц</span>
                                <i class="mdi mdi-information-outline"></i>
                            </span>
                            <br>
                                <i class="material-icons">description</i>
                                <a href="#" class="black-text" @click.prevent="attorney(c.Id)">
                                    Скачать Доверенность
                                </a>
                            <br>
                                <i class="material-icons">local_grocery_store</i>
                                <a href="#" class="black-text" @click.prevent="shipments(c.Id)">
                                    Сверка объемов (Отчет по отгрузкам)
                                </a>
                            <br>
                                <i class="material-icons">receipt</i>
                                <a :href="'https://is.smaip.ru/testvue/b24/ticket/ticket.php?id='+c.Deal_id" class="black-text" target="_blank">
                                  Квитанция на оплату
                                </a>
                            <br>
                                <i class="material-icons">work</i>
                                <a href="#" class="black-text" @click.prevent="add_contract(c.Id)">
                                  Договор
                                </a>
                            <br>
                                <i class="material-icons">pets</i>
                                <a href="#" class="black-text" @click.prevent="farm_certificate(c.Id)">
                                  Справка о фермерском хозяйстве
                                </a>
                            <br>
                                <i class="material-icons">photo_camera</i>
                                <a href="#" class="black-text" @click.prevent="foto(c.Id)">
                                  Фото отчеты
                                </a>
                            <br>
                                <i class="material-icons">event_note</i>
                                <a href="#" class="black-text" @click.prevent="invoice(c.Id)">
                                  Накладные
                                </a>
                            <br>
                            <i class="menu-arrow  arrow_sheep mdi-24px float-right"></i></h6>
                        <div class="mt-3 collapse" id="ui-basic0" style=""></div>
                        <div class="d-flex justify-content-center"><div class="spinner-border" role="status" id="loader20" style="display: none;"></div></div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
export default {
  name: 'Home',
  computed: {
    ...mapGetters([
      'USER',
      'COMPANY'
    ])
  },
  methods: {
    ...mapActions([
      'GET_COMPANY_USER',
      'SET_CURRENTOBJECT'
    ]),
    btnRefresh () {
      this.GET_COMPANY_USER(this.USER)
      // console.log('button refresh')
    //   console.log(this.USER)
    },
    attorney (id) {
      // console.log(id)
      this.SET_CURRENTOBJECT(id)
      this.$router.push('/attorney')
    },
    shipments (id) {
      // console.log(id)
      this.SET_CURRENTOBJECT(id)
      this.$router.push('/shipments')
    },
    farm_certificate (id) {
      // console.log(id)
      this.SET_CURRENTOBJECT(id)
      this.$router.push('/farm_certificate')
    },
    foto (id) {
      // console.log(id)
      this.SET_CURRENTOBJECT(id)
      this.$router.push('/foto')
    },
    add_contract (id) {
      // console.log(id)
      this.SET_CURRENTOBJECT(id)
      this.$router.push('/add_contract')
    },
    invoice (id) {
      // console.log(id)
      this.SET_CURRENTOBJECT(id)
      this.$router.push('/invoice')
    }
  },
  mounted () {
    if (this.COMPANY.length <= 1) {
      this.GET_COMPANY_USER(this.USER)
    } else {
    //   console.log(typeof this.COMPANY)
    }
  }
}
</script>
