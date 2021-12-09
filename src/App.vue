<template>
    <div>
        <component :is="layout">
            <router-view/>
        </component>
    </div>
</template>

<script>
import LEmptyLayout from '@/layouts/l-empty-layout.vue'
import LMainLayout from '@/layouts/l-main-layout.vue'

export default {
  computed: {
    layout () {
      return 'l-' + (this.$route.meta.layout || 'empty') + '-layout'
    }
  },
  components: {
    LEmptyLayout,
    LMainLayout
  }
}
</script>

<style>
@import '~materialize-css/dist/css/materialize.min.css';
@import 'assets/index.css';
</style>
