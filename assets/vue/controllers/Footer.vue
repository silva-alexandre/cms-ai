<template>
  <footer class="footer py-5 mt-auto">
    <div class="container text-center animate-on-scroll">
      <div class="footer-logo mb-4">
        <img src="https://gr-pintura.com.br/gr-pintura-logo.png" height="50" class="grayscale opacity-50">
      </div>
      
      <div class="divider-gold mx-auto mb-4" style="width: 50px;"></div>
      
      <p class="text-salvia mb-3 opacity-75">
        &copy; {{ currentYear }} GR Pintura. Excelência em Acabamentos e Proteção.
      </p>
      
      <div class="social-links d-flex justify-content-center gap-4 mb-4">
        <a href="#" class="footer-link" title="Instagram">
          <i class="bi bi-instagram fs-4"></i>
        </a>
        <a href="#" class="footer-link" title="Facebook">
          <i class="bi bi-facebook fs-4"></i>
        </a>
        <a href="https://wa.me/55" class="footer-link" title="WhatsApp">
          <i class="bi bi-whatsapp fs-4"></i>
        </a>
      </div>

      <nav class="footer-nav d-flex justify-content-center gap-3 flex-wrap">
        <a v-for="(link, index) in footerLinks" :key="index" :href="link.href" class="footer-nav-link text-decoration-none">
          {{ link.label }}
        </a>
      </nav>
    </div>
  </footer>
</template>

<script setup>
import { ref } from 'vue'

const currentYear = ref(new Date().getFullYear())

const footerLinks = [
  { label: 'Início', href: '/' },
  { label: 'Serviços', href: '#servicos' },
  { label: 'Sobre', href: '#about' },
  { label: 'Simulador', href: '#simulador-orcamento' },
  { label: 'Privacidade', href: '#' }
]
</script>

<style scoped>
.footer {
  background: #1b3328;
  border-top: 1px solid rgba(176, 141, 87, 0.15);
  position: relative;
}

.text-salvia { color: #A8B5A8; }

.divider-gold {
  height: 3px;
  background: #B08D57;
  border-radius: 2px;
}

.footer-link {
  color: #A8B5A8;
  transition: all 0.3s ease;
  display: inline-block;
}

.footer-link:hover {
  color: #B08D57;
  transform: translateY(-3px);
}

.footer-nav-link {
  color: #A8B5A8;
  font-size: 0.9rem;
  font-weight: 500;
  transition: color 0.3s ease;
  opacity: 0.8;
}

.footer-nav-link:hover {
  color: #F2E8CF;
  opacity: 1;
}

.grayscale { filter: grayscale(1); }

@media (max-width: 768px) {
  .footer-nav {
    flex-direction: column;
    gap: 15px !important;
  }
}
</style>
