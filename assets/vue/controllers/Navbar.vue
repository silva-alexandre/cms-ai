<template>
  <nav class="navbar">
    <div class="logo">CMS Dev</div>

    <ul class="menu">
      <!-- Itens públicos -->
      <li><a href="/">Home</a></li>
      <li><a href="/sobre">Sobre</a></li>
      <li><a href="/servicos">Serviços</a></li>

      <!-- Itens autenticados -->
      <template v-if="isAuthenticated">
        <li><a href="/admin">Dashboard</a></li>
        <li v-if="isAdmin"><a href="/admin/users">Usuários</a></li>
        <li><a href="/logout">Sair</a></li>
      </template>

      <!-- Login -->
      <li v-else>
        <a href="/login">Login</a>
      </li>
    </ul>
  </nav>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  user: Object
})

const isAuthenticated = computed(() => !!props.user)

const isAdmin = computed(() => {
  return props.user?.roles?.includes('ROLE_ADMIN')
})
</script>

<style scoped>
.navbar {
  display: flex;
  justify-content: space-between;
  padding: 20px;
  background: #111;
  color: white;
}

.menu {
  display: flex;
  gap: 20px;
  list-style: none;
}

a {
  color: white;
  text-decoration: none;
}
</style>