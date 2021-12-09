<template>
    <ul class="sidenav app-sidenav" :class="{open: isOpen}">
            <router-link
                v-for="link in links"
                :key="link.url"
                active-class="activ"
                :to="link.url"
                custom
                v-slot="{ href, navigate, isExactActive }"
            >
                <li :class="[isExactActive && 'active']">
                    <a :href="href" @click="navigate" class="waves-effect waves-orange pointer">{{link.title}}</a>
                </li>
            </router-link>
    </ul>
</template>

<script>
export default {
  props: {
    isOpen: {
      type: Boolean,
      default: true
    }
  },
  data: () => ({
    links: [
      { title: 'Главная', url: '/' },
      { title: 'Договор', url: '/contract' },
      { title: 'Инструкция', url: '/manual' },
      { title: 'Скачать Лист фиксанции отсутствия отгрузок', url: '/lf' }
      // { title: 'Новая запись', url: '/record' },
      // { title: 'Категории', url: '/categories' }
    ]
  })
}
</script>
