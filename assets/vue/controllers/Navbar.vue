<template>
  <nav 
    class="navbar navbar-expand-lg navbar-dark fixed-top transition-all" 
    :class="{ 'scrolled': isScrolled }"
  >
    <div class="container">
      <a class="navbar-brand" href="/" aria-label="CMS Dev Home">
        CMS<span>Dev</span>
      </a>
      
      <button 
        class="navbar-toggler border-0" 
        type="button" 
        @click="isMobileMenuOpen = !isMobileMenuOpen"
        aria-controls="navbarNav" 
        :aria-expanded="isMobileMenuOpen" 
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div 
        class="collapse navbar-collapse" 
        :class="{ 'show': isMobileMenuOpen }" 
        id="navbarNav"
      >
        <ul class="navbar-nav ms-auto align-items-lg-center">
          <li class="nav-item" v-for="item in menuItems" :key="item.label">
            <a 
              class="nav-link" 
              :href="item.href"
              :class="{ 'active': item.active }"
            >
              {{ item.label }}
            </a>
          </li>

          <!-- Itens autenticados -->
          <template v-if="isAuthenticated">
            <li class="nav-item">
              <a class="nav-link" href="/admin">Dashboard</a>
            </li>
            <li class="nav-item" v-if="isAdmin">
              <a class="nav-link" href="/admin/users">Usuários</a>
            </li>
            <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
              <a href="/logout" class="btn btn-logout px-4">Sair</a>
            </li>
          </template>

          <!-- Login -->
          <li v-else class="nav-item ms-lg-3 mt-2 mt-lg-0">
            <a href="/login" class="btn btn-primary-nav px-4">Login</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'

const props = defineProps({
  user: {
    type: Object,
    default: null
  },
  menuItems: {
    type: Array,
    default: () => [
      { label: 'Início', href: '/', active: true },
      { label: 'Sobre', href: '/sobre', active: false },
      { label: 'Serviços', href: '/servicos', active: false }
    ]
  }
})

// === Estado Reativo ===
const isScrolled = ref(false)
const isMobileMenuOpen = ref(false)

// === Computed ===
const isAuthenticated = computed(() => !!props.user)
const isAdmin = computed(() => props.user?.roles?.includes('ROLE_ADMIN'))

// === Métodos ===
const handleScroll = () => {
  isScrolled.value = window.scrollY > 50
}

// === Ciclo de Vida ===
onMounted(() => {
  window.addEventListener('scroll', handleScroll, { passive: true })
  handleScroll()
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
})
</script>

<style scoped>
/* Variáveis baseadas no Home.vue */
:root {
  --primary-color: #2950bc;
  --primary-dark: #1e3a8a;
  --primary-light: #6ac4ff;
  --text-light: #ffffff;
  --text-muted: #acbad1;
  --glass-bg: rgba(255, 255, 255, 0.1);
  --glass-border: rgba(255, 255, 255, 0.2);
  --nav-bg-scrolled: rgba(22, 33, 62, 0.95);
}

.transition-all {
  transition: all 0.3s ease;
}

.navbar {
  padding: 1.25rem 0;
  background: transparent;
  z-index: 1030;
}

.navbar.scrolled {
  background: var(--nav-bg-scrolled);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  padding: 0.75rem 0;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
  border-bottom: 1px solid var(--glass-border);
}

.navbar-brand {
  font-weight: 700;
  color: #ffffff !important;
  font-size: 1.5rem;
  letter-spacing: -0.5px;
}

.navbar-brand span {
  color: #6ac4ff; /* primary-light */
}

.nav-link {
  color: #acbad1 !important; /* text-muted */
  font-weight: 500;
  padding: 0.5rem 1.25rem !important;
  transition: color 0.2s ease;
  position: relative;
}

.nav-link:hover,
.nav-link.active {
  color: #ffffff !important;
}

.nav-link.active::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 1.25rem;
  right: 1.25rem;
  height: 2px;
  background: #6ac4ff;
  border-radius: 2px;
}

/* Botões Customizados */
.btn-primary-nav {
  background: linear-gradient(135deg, #2950bc, #1e3a8a);
  border: none;
  color: white;
  font-weight: 600;
  border-radius: 8px;
  padding: 0.6rem 1.5rem;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(41, 80, 188, 0.3);
}

.btn-primary-nav:hover {
  transform: translateY(-1px);
  box-shadow: 0 6px 15px rgba(41, 80, 188, 0.5);
  filter: brightness(1.1);
}

.btn-logout {
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
  color: white;
  font-weight: 500;
  border-radius: 8px;
  padding: 0.6rem 1.5rem;
  transition: all 0.2s ease;
}

.btn-logout:hover {
  background: rgba(231, 76, 60, 0.2);
  border-color: rgba(231, 76, 60, 0.4);
  color: #e74c3c;
}

/* Mobile adjustments */
@media (max-width: 991.98px) {
  .navbar-collapse {
    background: #1a1a2e;
    margin-top: 1rem;
    padding: 1.5rem;
    border-radius: 12px;
    border: 1px solid var(--glass-border);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
  }
  
  .nav-link.active::after {
    display: none;
  }
  
  .nav-link {
    padding: 0.75rem 0 !important;
  }
}

@media (max-width: 768px) {
  .navbar {
    padding: 0.75rem 0;
  }

  .navbar-brand {
    font-size: 1.25rem;
  }

  .navbar-collapse {
    padding: 1rem;
    margin-top: 0.5rem;
  }
}

.navbar-toggler:focus {
  box-shadow: none;
}
</style>