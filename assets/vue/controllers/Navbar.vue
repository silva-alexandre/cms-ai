<template>
  <nav class="navbar navbar-expand-lg navbar-dark navbar-custom" :class="{ 'scrolled': isScrolled }">
    <div class="container">
      <a class="navbar-brand" href="/">
        <img src="https://gr-pintura.com.br/gr-pintura-logo.png" alt="GR Pintura Logo" class="brand-img">
      </a>

      <button 
        class="navbar-toggler" 
        type="button" 
        @click="isMobileMenuOpen = !isMobileMenuOpen"
        aria-controls="menu" 
        :aria-expanded="isMobileMenuOpen" 
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" :class="{ 'show': isMobileMenuOpen }" id="menu">
        <ul class="navbar-nav align-items-center">
          <li class="nav-item">
            <a class="nav-link" href="#features">Serviços</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#about">Sobre</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#contact">Contato</a>
          </li>

          <!-- Itens autenticados -->
          <template v-if="isAuthenticated">
            <li class="nav-item">
              <a class="nav-link" href="/admin">Dashboard</a>
            </li>
            <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
              <a href="/logout" class="btn btn-logout px-4">Sair</a>
            </li>
          </template>

          <!-- Orçamento / Login -->
          <li v-else class="nav-item ms-lg-3 mt-2 mt-lg-0">
            <a href="#simulador" class="btn btn-nav">
              <i class="bi bi-calculator me-1"></i>
              Simular Orçamento
            </a>
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
  }
})

// === Estado Reativo ===
const isScrolled = ref(false)
const isMobileMenuOpen = ref(false)

// === Computed ===
const isAuthenticated = computed(() => !!props.user)

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
.navbar-custom {
  position: absolute;
  top: 0;
  width: 100%;
  z-index: 1000;
  padding: 18px 0;
  transition: .3s;
  background: transparent;
}

.navbar-custom.scrolled {
  position: fixed;
  background: rgba(40, 75, 59, .95);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  padding: 12px 0;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}

.brand-img {
  height: 70px;
  transition: .3s;
}

.navbar-custom.scrolled .brand-img {
  height: 50px;
}

.navbar-nav .nav-link {
  color: #F2E8CF; /* crema-suave */
  font-weight: 500;
  margin-left: 18px;
  transition: color .2s;
}

.navbar-nav .nav-link:hover,
.navbar-nav .nav-link.active {
  color: white;
}

.btn-nav {
  background: #B08D57; /* bronze-antigo */
  color: white;
  padding: 10px 22px;
  border-radius: 10px;
  font-weight: 600;
  transition: .2s;
  border: none;
  text-decoration: none;
  display: inline-block;
}

.btn-nav:hover {
  background: #c19b62;
  color: white;
  transform: translateY(-2px);
}

.btn-logout {
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(242, 232, 207, 0.2);
  color: #F2E8CF;
  border-radius: 8px;
  text-decoration: none;
}

.btn-logout:hover {
  background: rgba(231, 76, 60, 0.2);
  color: #e74c3c;
}

/* Mobile Adjustments */
@media (max-width: 991.98px) {
  .navbar-custom {
    padding: 12px 0;
    background: rgba(40, 75, 59, .96);
    position: fixed;
  }

  .brand-img {
    height: 48px;
  }

  .navbar-nav {
    margin-top: 20px;
    text-align: center;
    background: rgba(0, 0, 0, 0.1);
    padding: 20px;
    border-radius: 12px;
  }

  .navbar-nav .nav-link {
    margin: 10px 0;
    font-size: 1.05rem;
  }

  .btn-nav {
    margin-top: 15px;
    width: 100%;
    display: block;
  }
}

@media (max-width: 767.98px) {
  .navbar-custom {
    padding: 8px 0;
  }
  
  .brand-img {
    height: 40px;
  }
}
</style>