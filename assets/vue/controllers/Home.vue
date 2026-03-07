<template>
  <!-- Skip link para acessibilidade -->
  <a href="#main-content" class="skip-link">Pular para o conteúdo principal</a>
  <!-- Hero Section -->
  <header class="hero">
    <div class="container">
      <div class="hero-header">
        <div></div>
        <a href="#" class="logo" aria-label="Página inicial">
          Sua<span>Marca</span>
        </a>
      </div>
      
      <div class="glass-panel hero-content animate-on-scroll" ref="heroContent">
        <h1>{{ hero.title }}</h1>
        <p>{{ hero.subtitle }}</p>
        
        <form class="cta-form" @submit.prevent="handleContactSubmit">
          <label for="email-hero" class="visually-hidden">Seu e-mail</label>
          <input 
            type="email" 
            id="email-hero" 
            class="form-control" 
            v-model="formData.email"
            placeholder="seu@email.com" 
            required 
            aria-required="true"
            :disabled="isSubmitting"
          >
          <button type="submit" class="btn btn-primary" :disabled="isSubmitting">
            {{ isSubmitting ? 'Enviando...' : 'Solicitar contato' }}
          </button>
        </form>
        <div v-if="formMessage" :class="['form-feedback', formMessage.type]">
          {{ formMessage.text }}
        </div>
      </div>
    </div>
  </header>

  <!-- Conteúdo Principal -->
  <main id="main-content">
    
    <!-- Seção de Recursos -->
    <section id="features" class="section">
      <div class="container">
        <div class="row justify-content-center text-center mb-5">
          <div class="col-lg-8">
            <h2 class="section-title">{{ features.title }}</h2>
            <p class="section-subtitle">{{ features.subtitle }}</p>
          </div>
        </div>
        
        <div class="row g-4">
          <article 
            class="col-md-4" 
            v-for="(feature, index) in features.items" 
            :key="index"
          >
            <div 
              class="feature-card animate-on-scroll" 
              :style="{ animationDelay: `${index * 0.1}s` }"
              @mouseenter="onFeatureHover(index)"
              @mouseleave="onFeatureLeave(index)"
            >
              <div class="feature-icon">{{ feature.icon }}</div>
              <h5>{{ feature.title }}</h5>
              <p>{{ feature.description }}</p>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!-- Seção Sobre -->
    <section id="about" class="section" :style="{ background: 'rgba(30, 30, 50, 0.5)' }">
      <div class="container">
        <div class="row align-items-center g-5">
          <div class="col-lg-6">
            <h2 class="section-title">{{ about.title }}</h2>
            <p class="text-muted mb-4">{{ about.description1 }}</p>
            <p class="text-muted">{{ about.description2 }}</p>
            <a href="#contact" class="btn btn-primary mt-4" @click.prevent="scrollToContact">
              {{ about.ctaText }}
            </a>
          </div>
          <div class="col-lg-6">
            <div class="glass-panel about-visual">
              <slot name="about-visual">
                <p class="text-muted mb-0">{{ about.imagePlaceholder }}</p>
              </slot>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Seção de Contato -->
    <section id="contact" class="section" ref="contactSection">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8 text-center">
            <h2 class="section-title">{{ contact.title }}</h2>
            <p class="section-subtitle mx-auto">{{ contact.subtitle }}</p>
            
            <form 
              class="cta-form mx-auto" 
              style="max-width: 500px;" 
              @submit.prevent="handleContactSubmit"
            >
              <label for="email-contact" class="visually-hidden">Seu e-mail</label>
              <input 
                type="email" 
                id="email-contact" 
                class="form-control" 
                v-model="formData.email"
                placeholder="seu@email.com" 
                required
                :disabled="isSubmitting"
              >
              <button type="submit" class="btn btn-primary" :disabled="isSubmitting">
                {{ isSubmitting ? 'Enviando...' : 'Solicitar contato' }}
              </button>
            </form>
            <div v-if="formMessage" :class="['form-feedback', formMessage.type, 'mt-3']">
              {{ formMessage.text }}
            </div>
          </div>
        </div>
      </div>
    </section>

  </main>

  <!-- Footer -->
  <footer class="footer">
    <div class="container text-center">
      <p>&copy; {{ currentYear }} {{ footer.companyName }}. Todos os direitos reservados.</p>
      <p class="mt-2">
        <a v-for="(link, index) in footer.links" :key="index" :href="link.href" class="text-decoration-none footer-link">
          {{ link.label }}
        </a>
      </p>
    </div>
  </footer>
</template>

<script setup>
import { ref, reactive, onMounted, onUnmounted, nextTick } from 'vue'

// === Props recebidas do Twig ===
const props = defineProps({
  hero: {
    type: Object,
    default: () => ({
      title: 'Soluções inteligentes para o seu negócio digital',
      subtitle: 'Transforme ideias em experiências excepcionais com tecnologia de ponta e design centrado no usuário.'
    })
  },
  features: {
    type: Object,
    default: () => ({
      title: 'Por que nos escolher?',
      subtitle: 'Combinamos inovação tecnológica com práticas modernas de UX para entregar resultados excepcionais.',
      items: [
        {
          icon: '✨',
          title: 'Design Moderno',
          description: 'Interfaces minimalistas e tecnológicas que seguem as melhores práticas de UX e acessibilidade.'
        },
        {
          icon: '🚀',
          title: 'Performance Otimizada',
          description: 'Código limpo e carregamento rápido para uma experiência fluida em qualquer dispositivo.'
        },
        {
          icon: '🔒',
          title: 'Segurança & Confiabilidade',
          description: 'Arquitetura robusta com foco em proteção de dados e estabilidade para seu negócio.'
        }
      ]
    })
  },
  about: {
    type: Object,
    default: () => ({
      title: 'Sobre nossa abordagem',
      description1: 'Trabalhamos com foco em resultados, utilizando metodologias ágeis e feedback contínuo para garantir que cada projeto supere expectativas.',
      description2: 'Nossa equipe especializada em desenvolvimento web, design de interface e estratégia digital está pronta para transformar sua visão em realidade.',
      ctaText: 'Conheça nosso time',
      imagePlaceholder: '[Área para imagem/ilustração futurista com elementos de IA e fluxos de dados]'
    })
  },
  contact: {
    type: Object,
    default: () => ({
      title: 'Pronto para começar?',
      subtitle: 'Deixe seu e-mail e entraremos em contato para entender como podemos ajudar seu projeto a decolar.'
    })
  },
  footer: {
    type: Object,
    default: () => ({
      companyName: 'SuaMarca',
      links: [
        { label: 'Política de Privacidade', href: '#' },
        { label: 'Termos de Uso', href: '#' }
      ]
    })
  },
  apiEndpoint: {
    type: String,
    default: '/api/contact'
  }
})

// === Emits para comunicação com Twig/Stimulus ===
const emit = defineEmits(['contact-submitted', 'nav-click', 'feature-hover'])

// === Estado Reativo ===
const isScrolled = ref(false)
const isMobileMenuOpen = ref(false)
const isSubmitting = ref(false)
const formMessage = ref(null)
const currentYear = ref(new Date().getFullYear())

const formData = reactive({
  email: ''
})

const navItems = reactive([
  { label: 'Início', href: '#', active: true, target: 'hero' },
  { label: 'Recursos', href: '#features', active: false, target: 'features' },
  { label: 'Sobre', href: '#about', active: false, target: 'about' },
  { label: 'Contato', href: '#contact', active: false, target: 'contact' }
])

// Refs para elementos do DOM
const heroContent = ref(null)
const contactSection = ref(null)
const observer = ref(null)

// === Métodos ===
const handleScroll = () => {
  isScrolled.value = window.scrollY > 50
  
  // Atualizar item ativo da navbar baseado no scroll
  updateActiveNavItem()
}

const updateActiveNavItem = () => {
  const sections = ['hero', 'features', 'about', 'contact']
  const scrollPosition = window.scrollY + 150 // offset para navbar
  
  sections.forEach(sectionId => {
    const section = document.getElementById(sectionId)
    if (section) {
      const sectionTop = section.offsetTop
      const sectionHeight = section.offsetHeight
      
      if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
        navItems.forEach(item => {
          item.active = item.target === sectionId
        })
      }
    }
  })
}

const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value
}

const handleNavClick = (item) => {
  emit('nav-click', item)
  
  if (item.target === 'hero') {
    window.scrollTo({ top: 0, behavior: 'smooth' })
  } else {
    const target = document.getElementById(item.target)
    if (target) {
      const headerOffset = 80
      const elementPosition = target.getBoundingClientRect().top
      const offsetPosition = elementPosition + window.pageYOffset - headerOffset

      window.scrollTo({
        top: offsetPosition,
        behavior: 'smooth'
      })
    }
  }
  
  // Fechar menu mobile após clique
  if (window.innerWidth < 992) {
    isMobileMenuOpen.value = false
  }
}

const scrollToContact = () => {
  emit('nav-click', { target: 'contact' })
  
  if (contactSection.value) {
    const headerOffset = 80
    const elementPosition = contactSection.value.getBoundingClientRect().top
    const offsetPosition = elementPosition + window.pageYOffset - headerOffset

    window.scrollTo({
      top: offsetPosition,
      behavior: 'smooth'
    })
  }
}

const handleContactSubmit = async () => {
  if (!formData.email) {
    showFormMessage('Por favor, informe um e-mail válido.', 'error')
    return
  }
  
  isSubmitting.value = true
  formMessage.value = null
  
  try {
    // Simulação de chamada API - substitua pela chamada real
    await new Promise(resolve => setTimeout(resolve, 1500))
    
    // Exemplo de chamada real:
    // const response = await fetch(props.apiEndpoint, {
    //   method: 'POST',
    //   headers: { 'Content-Type': 'application/json' },
    //   body: JSON.stringify({ email: formData.email })
    // })
    // if (!response.ok) throw new Error('Erro ao enviar')
    
    showFormMessage('Obrigado! Entraremos em contato em breve.', 'success')
    formData.email = ''
    
    // Emitir evento para Twig/Stimulus
    emit('contact-submitted', { email: formData.email, success: true })
    
  } catch (error) {
    showFormMessage('Ocorreu um erro ao enviar. Tente novamente.', 'error')
    emit('contact-submitted', { email: formData.email, success: false, error: error.message })
  } finally {
    isSubmitting.value = false
  }
}

const showFormMessage = (text, type) => {
  formMessage.value = { text, type }
  setTimeout(() => {
    formMessage.value = null
  }, 5000)
}

const onFeatureHover = (index) => {
  emit('feature-hover', { index, hovered: true })
}

const onFeatureLeave = (index) => {
  emit('feature-hover', { index, hovered: false })
}

const initScrollAnimations = () => {
  // Observer para animações de entrada ao scroll
  observer.value = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = '1'
        entry.target.style.transform = 'translateY(0)'
        observer.value?.unobserve(entry.target)
      }
    })
  }, {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  })

  // Aplicar observer nos elementos com classe animate-on-scroll
  nextTick(() => {
    document.querySelectorAll('.animate-on-scroll').forEach(el => {
      el.style.opacity = '0'
      el.style.transform = 'translateY(20px)'
      el.style.transition = 'opacity 0.6s ease, transform 0.6s ease'
      observer.value?.observe(el)
    })
  })
}

const cleanupAnimations = () => {
  if (observer.value) {
    observer.value.disconnect()
  }
}

// === Ciclo de Vida ===
onMounted(() => {
  // Listener de scroll
  window.addEventListener('scroll', handleScroll, { passive: true })
  
  // Inicializar animações
  initScrollAnimations()
  
  // Inicializar estado da navbar
  handleScroll()
  
  // Inicializar Bootstrap Collapse se necessário
  import('bootstrap').then(({ Collapse }) => {
    const navbarCollapse = document.getElementById('navbarNav')
    if (navbarCollapse && !navbarCollapse.classList.contains('initialized')) {
      new Collapse(navbarCollapse, { toggle: false })
      navbarCollapse.classList.add('initialized')
    }
  }).catch(() => {
    // Bootstrap não disponível, usar fallback manual
  })
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
  cleanupAnimations()
})
</script>

<style scoped>
:root {
  --primary-color: #2950bc;
  --primary-dark: #1e3a8a;
  --primary-light: #6ac4ff;
  --text-light: #ffffff;
  --text-muted: #acbad1;
  --glass-bg: rgba(255, 255, 255, 0.1);
  --glass-border: rgba(255, 255, 255, 0.2);
  --gradient-start: #1a1a2e;
  --gradient-end: #16213e;
  --card-hover-border: rgba(106, 196, 255, 0.5);
}

* {
  box-sizing: border-box;
}

:host {
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
  background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
  color: var(--text-light);
  line-height: 1.6;
  overflow-x: hidden;
  display: block;
  min-height: 100vh;
}

/* === Utilitários de Acessibilidade === */
.skip-link {
  position: absolute;
  top: -40px;
  left: 0;
  background: var(--primary-color);
  color: white;
  padding: 0.5rem 1rem;
  z-index: 100;
  transition: top 0.3s;
  text-decoration: none;
  border-radius: 0 0 8px 0;
}

.skip-link:focus {
  top: 0;
}

.visually-hidden {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  white-space: nowrap;
  border: 0;
}

/* === Hero Section com Glassmorphism === */
.hero {
  position: relative;
  min-height: 100vh;
  display: flex;
  align-items: center;
  background: 
    linear-gradient(135deg, rgba(41, 80, 188, 0.9), rgba(26, 26, 46, 0.95)),
    url('https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=1920&q=80') center/cover no-repeat fixed;
  padding: 2rem 0;
}

.hero::before {
  content: '';
  position: absolute;
  inset: 0;
  background: radial-gradient(ellipse at top, rgba(106, 196, 255, 0.15), transparent 70%);
  pointer-events: none;
}

.glass-panel {
  background: var(--glass-bg);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 1px solid var(--glass-border);
  border-radius: 16px;
  padding: 2.5rem;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
}

/* Logo posicionado à direita */
.hero-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 3rem;
}

.logo {
  font-weight: 700;
  font-size: 1.5rem;
  color: var(--text-light);
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: opacity 0.2s ease;
}

.logo:hover {
  opacity: 0.9;
}

.logo span {
  color: var(--primary-light);
}

/* CTA à esquerda */
.hero-content {
  max-width: 600px;
}

.hero h1 {
  font-size: clamp(2rem, 5vw, 3.5rem);
  font-weight: 700;
  line-height: 1.2;
  margin-bottom: 1.5rem;
  background: linear-gradient(to right, #fff, var(--text-muted));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.hero p {
  color: var(--text-muted);
  font-size: 1.125rem;
  margin-bottom: 2rem;
  max-width: 500px;
}

/* Formulário de captura */
.cta-form {
  display: flex;
  gap: 0.75rem;
  flex-wrap: wrap;
}

.cta-form .form-control {
  flex: 1;
  min-width: 200px;
  background: rgba(255, 255, 255, 0.15);
  border: 1px solid var(--glass-border);
  color: var(--text-light);
  padding: 0.875rem 1.25rem;
  border-radius: 10px;
  transition: all 0.3s ease;
}

.cta-form .form-control::placeholder {
  color: var(--text-muted);
  opacity: 1;
}

.cta-form .form-control:focus {
  background: rgba(255, 255, 255, 0.25);
  border-color: var(--primary-light);
  box-shadow: 0 0 0 3px rgba(106, 196, 255, 0.2);
  outline: none;
  color: var(--text-light);
}

.cta-form .form-control:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.btn-primary {
  background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
  border: none;
  padding: 0.875rem 2rem;
  border-radius: 10px;
  font-weight: 600;
  color: white;
  transition: all 0.3s ease;
  box-shadow: 0 4px 14px rgba(41, 80, 188, 0.4);
  cursor: pointer;
}

.btn-primary:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(41, 80, 188, 0.6);
  background: linear-gradient(135deg, var(--primary-dark), #162e7a);
  color: white;
}

.btn-primary:active:not(:disabled) {
  transform: translateY(0);
}

.btn-primary:disabled {
  opacity: 0.7;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
}

/* Feedback do formulário */
.form-feedback {
  margin-top: 1rem;
  padding: 0.75rem 1rem;
  border-radius: 8px;
  font-size: 0.9rem;
  animation: fadeIn 0.3s ease;
}

.form-feedback.success {
  background: rgba(46, 204, 113, 0.2);
  border: 1px solid rgba(46, 204, 113, 0.4);
  color: #2ecc71;
}

.form-feedback.error {
  background: rgba(231, 76, 60, 0.2);
  border: 1px solid rgba(231, 76, 60, 0.4);
  color: #e74c3c;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}

/* === Navbar moderna === */
.navbar {
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 1030;
  padding: 1rem 0;
  transition: all 0.3s ease;
  background: transparent;
}

.navbar.scrolled {
  background: rgba(22, 33, 62, 0.95);
  backdrop-filter: blur(10px);
  padding: 0.75rem 0;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}

.navbar-brand {
  font-weight: 700;
  color: var(--text-light) !important;
  font-size: 1.25rem;
}

.navbar-brand span {
  color: var(--primary-light);
}

:deep(.navbar-dark .navbar-nav .nav-link) {
  color: var(--text-muted);
  font-weight: 500;
  padding: 0.5rem 1rem;
  transition: color 0.2s ease;
}

:deep(.navbar-dark .navbar-nav .nav-link:hover),
:deep(.navbar-dark .navbar-nav .nav-link.active) {
  color: var(--text-light);
}

:deep(.navbar-collapse) {
  transition: none;
}

:deep(.navbar-collapse.show) {
  display: block;
}

@media (max-width: 991px) {
  :deep(.navbar-collapse:not(.show)) {
    display: none;
  }
}

/* === Seções de conteúdo === */
.section {
  padding: 5rem 0;
}

.section-title {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 1rem;
  color: var(--text-light);
}

.section-subtitle {
  color: var(--text-muted);
  font-size: 1.1rem;
  margin-bottom: 3rem;
  max-width: 600px;
}

/* === Cards com efeito glass === */
.feature-card {
  background: var(--glass-bg);
  border: 1px solid var(--glass-border);
  border-radius: 16px;
  padding: 2rem;
  height: 100%;
  transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
  cursor: default;
}

.feature-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);
  border-color: var(--card-hover-border);
}

.feature-icon {
  width: 56px;
  height: 56px;
  background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 1.25rem;
  font-size: 1.5rem;
  flex-shrink: 0;
}

.feature-card h5 {
  color: var(--text-light);
  font-weight: 600;
  margin-bottom: 0.75rem;
}

.feature-card p {
  color: var(--text-muted);
  margin: 0;
  font-size: 0.95rem;
}

/* Visual da seção Sobre */
.about-visual {
  min-height: 300px;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
}

/* === Footer === */
.footer {
  background: rgba(15, 15, 30, 0.95);
  border-top: 1px solid var(--glass-border);
  padding: 3rem 0 1.5rem;
  margin-top: 4rem;
}

.footer p {
  color: var(--text-muted);
  margin: 0;
  font-size: 0.9rem;
}

.footer-link {
  color: var(--text-muted);
  text-decoration: none;
  transition: color 0.2s ease;
  margin: 0 0.5rem;
  display: inline-block;
}

.footer-link:hover {
  color: var(--primary-light);
}

.footer-link:not(:last-child)::after {
  content: '•';
  margin-left: 0.5rem;
  color: var(--glass-border);
}

/* === Responsividade === */
@media (max-width: 768px) {
  .hero {
    padding: 6rem 0 3rem; /* Espaço para navbar fixa */
  }

  .hero-header {
    flex-direction: column;
    gap: 1rem;
    text-align: center;
    margin-bottom: 2rem;
  }
  
  .hero-header > div:first-child {
    display: none;
  }

  .hero h1 {
    font-size: 2.25rem;
    margin-bottom: 1rem;
  }

  .hero p {
    font-size: 1rem;
    margin-bottom: 1.5rem;
  }
  
  .cta-form {
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .cta-form .form-control,
  .cta-form .btn-primary {
    width: 100%;
    padding: 0.75rem 1.25rem;
  }
  
  .section {
    padding: 3.5rem 0;
  }
  
  .section-title {
    font-size: 1.75rem;
    margin-bottom: 0.75rem;
  }

  .section-subtitle {
    font-size: 1rem;
    margin-bottom: 2rem;
  }
  
  .feature-card {
    text-align: center;
    padding: 1.5rem;
  }
  
  .feature-icon {
    margin-left: auto;
    margin-right: auto;
  }

  .about-visual {
    min-height: 200px;
    margin-top: 2rem;
  }

  .footer {
    padding: 2.5rem 0 1rem;
    text-align: center;
  }

  .footer-link {
    display: block;
    margin: 0.5rem 0;
  }

  .footer-link:not(:last-child)::after {
    display: none;
  }
}

@media (max-width: 480px) {
  .hero {
    padding: 1rem 0;
  }
  
  .glass-panel {
    padding: 1.5rem;
  }
  
  .hero h1 {
    font-size: 1.75rem;
  }
  
  .hero p {
    font-size: 1rem;
  }
}

/* === Animações de entrada === */
.animate-on-scroll {
  will-change: transform, opacity;
}

/* === Utilitários de texto === */
.text-muted {
  color: var(--text-muted) !important;
}

/* === Links === */
a {
  color: inherit;
  text-decoration: none;
}

a:hover {
  text-decoration: none;
}

/* === Foco visível para acessibilidade === */
:focus-visible {
  outline: 2px solid var(--primary-light);
  outline-offset: 2px;
}

/* === Transições suaves para scroll === */
html {
  scroll-behavior: smooth;
}
</style>