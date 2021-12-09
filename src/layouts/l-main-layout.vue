<template>
    <NavBar @clickBtn="isOpen = !isOpen"/>
    <SideBar :isOpen="isOpen"/>
    <div class="app-main-layout">
        <main class="app-content" :class="{full: !isOpen}">
            <div class="app-page">
             <router-view />
            </div>
        </main>

        <div class="fixed-action-btn">
            <router-link class="btn-floating btn-large blue" to="/message">
                <i class="large material-icons">add</i>
            </router-link>
        </div>
    </div>
</template>

<script>
import NavBar from '@/components/app/vc-navbar'
import SideBar from '@/components/app/vc-sidebar'

export default {
  name: 'l-main-layout',
  data: () => ({
    isOpen: true
  }),
  components: {
    NavBar, SideBar
  }
}
</script>
