<template>
    <div>
        <div class="page-title">
            <h3>Профиль</h3>
        </div>
        {{ USER.Name }} <br>
        {{ USER.SecondName }} <br>
         {{ USER.LastName }} <br>
        <form class="form">
            <div class="input-field">
                <input
                    id="description"
                    type="text"
                >
                <label for="description">Имя</label>
                <span
                        class="helper-text invalid"></span>
            </div>
            <button class="btn waves-effect waves-light" type="submit">
            Обновить
            <i class="material-icons right">send</i>
            </button>
        </form>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  name: 'Profile',
  computed: {
    ...mapGetters([
      'USER'
    ])
  }
}
</script>
