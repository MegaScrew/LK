<template>
    <form class="card auth-card" @submit.prevent="submitHandler">
        <div class="card-content">
            <span class="card-title">Личный кабинет</span>
            <div class="input-field">
                <input
                    id="login"
                    type="text"
                    v-model.trim="login"
                    :class="{invalid: (v$.login.$dirty && v$.login.required)}"
                >
                <label for="login">Имя пользователя</label>
                <small
                  v-if="v$.login.$dirty && v$.login.mustBeLid.$invalid"
                  class="helper-text invalid"
                  >Введите коректное Имя пользователя
                </small>
                <small
                  v-if="v$.login.$dirty && v$.login.minLength.$invalid"
                  class="helper-text invalid"
                  >Короткое Имя пользователя
                </small>
                <small
                  v-if="v$.login.$dirty && v$.login.maxLength.$invalid"
                  class="helper-text invalid"
                  >Длинное Имя пользователя
                </small>
            </div>
            <div class="input-field">
                <input
                    id="password"
                    type="password"
                    v-model.trim="password"
                >
                <label for="password">Пароль</label>
                <small
                  class="helper-text invalid"
                  v-if="!v$.password.minLength"
                  >Некорректный пароль
                </small>
            </div>
        </div>
        <div class="card-action">
            <div>
                <button
                    class="btn waves-effect waves-light auth-submit"
                    type="submit"
                >
                    Войти
                    <i class="material-icons right">send</i>
                </button>
            </div>
            <p class="center">
            Нет аккаунта?
            <router-link to="/register" >Зарегистрироваться</router-link>
            </p>
        </div>
    </form>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import useVuelidate from '@vuelidate/core'
import { required, minLength, maxLength, alphaNum } from '@vuelidate/validators'
const mustBeLid = (value) => value.includes('lid')

export default {
  setup () {
    return { v$: useVuelidate() }
  },
  name: 'login',
  data: () => ({
    login: '',
    password: ''
  }),
  validations () {
    return {
      password: { required, minLength: minLength(6) },
      login: { required, mustBeLid, minLength: minLength(5), maxLength: maxLength(9), alphaNum }
    }
  },
  computed: {
    ...mapGetters([
      'USER'
    ])
  },
  methods: {
    ...mapActions([
      'LOGIN'
    ]),
    submitHandler () {
      if (this.v$.$invalid) {
        this.v$.$touch()
        return
      }

      const formData = {
        Steps: 'login',
        login: this.login,
        password: this.password
      }

      this.LOGIN(formData)
      // this.$store.dispatch('LOGIN', formData)
      // console.log(this.USER.IsActive)
      // if (this.USER.IsActive) {
      //   this.$router.push('/')
      // }
    }
  }
}
</script>
