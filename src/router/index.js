import { createRouter, createWebHistory } from 'vue-router'
import store from '../store'

const routes = [
  {
    path: '/login',
    name: 'Login',
    meta: { layout: 'empty' },
    component: () => import('../views/v-login.vue')
  },
  {
    path: '/register',
    name: 'Register',
    meta: { layout: 'empty' },
    component: () => import('../views/v-register.vue')
  },
  {
    path: '/',
    name: 'Home',
    meta: { layout: 'main', requiresAuth: true },
    component: () => import('../views/v-home.vue')
  },
  {
    path: '/contract',
    name: 'Сontract',
    meta: { layout: 'main', requiresAuth: true },
    component: () => import('../views/v-contract.vue')
  },
  {
    path: '/profile',
    name: 'Profile',
    meta: { layout: 'main', requiresAuth: true },
    component: () => import('../views/v-profile.vue')
  },
  {
    path: '/attorney',
    name: 'Attorney',
    meta: { layout: 'main', requiresAuth: true },
    component: () => import('../views/v-attorney.vue')
  },
  {
    path: '/shipments',
    name: 'Shipments',
    meta: { layout: 'main', requiresAuth: true },
    component: () => import('../views/v-shipments.vue')
  },
  {
    path: '/manual',
    name: 'Manual',
    meta: { layout: 'main', requiresAuth: true },
    component: () => import('../views/v-manual.vue')
  },
  {
    path: '/lf',
    name: 'Lf',
    meta: { layout: 'main', requiresAuth: true },
    component: () => import('../views/v-lf.vue')
  },
  {
    path: '/message',
    name: 'Message',
    meta: { layout: 'main', requiresAuth: true },
    component: () => import('../views/v-message.vue')
  },
  {
    path: '/farm_certificate',
    name: 'Farm_certificate',
    meta: { layout: 'main', requiresAuth: true },
    component: () => import('../views/v-farm_certificate.vue')
  },
  {
    path: '/foto',
    name: 'Foto',
    meta: { layout: 'main', requiresAuth: true },
    component: () => import('../views/v-foto.vue')
  },
  {
    path: '/invoice',
    name: 'Invoice',
    meta: { layout: 'main', requiresAuth: true },
    component: () => import('../views/v-invoice.vue')
  },
  {
    path: '/add_contract',
    name: 'Add_contract',
    meta: { layout: 'main', requiresAuth: true },
    component: () => import('../views/v-add_contract.vue')
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (store.getters.IS_ACTIVE) {
      next()
      return
    }
    next('/login')
  } else {
    next()
  }
})

export default router
