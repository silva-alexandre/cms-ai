<template>
  <div class="hero">
    <!-- Background Slider -->
    <div class="hero-bg-container">
      <div 
        v-for="(img, index) in heroImages" 
        :key="index"
        class="hero-slide"
        :class="{ 'active': currentSlide === index }"
        :style="{ backgroundImage: `url(${img})` }"
      ></div>
      <div class="hero-overlay"></div>
    </div>

    <div class="container overflow-hidden position-relative" style="z-index: 5;">
      <div class="row align-items-center mb-5">
        <div class="col-lg-7">
          <div class="glass-card animate-on-scroll">
            <span class="offer-badge mb-3">
              <i class="bi bi-fire me-1"></i> Consulte nossos serviços
            </span>

            <h1 class="display-4 fw-800 mb-4 text-crema">
              {{ hero.title || 'Cores que inspiram e resultados que impressionam' }}
            </h1>

            <p class="lead mb-4 text-salvia">
              Transformamos ambientes com cores vibrantes e acabamento impecável.
              <strong class="text-white">Simule o valor do seu orçamento agora!</strong>
            </p>

            <div class="d-flex gap-3 flex-wrap">
              <a href="#simulador" class="btn-main btn-lg">
                <i class="bi bi-whatsapp me-2"></i> Simular Orçamento
              </a>
              <a href="#features" class="btn-outline-crema btn-lg">
                Ver Portfólio
              </a>
            </div>

            <div class="hero-rating mt-4">
              <div class="stars mb-2">
                <i class="bi bi-star-fill text-warning" v-for="i in 5" :key="i"></i>
              </div>
              <small class="text-salvia opacity-75">4.9/5 em mais de 200 avaliações reais</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <main id="main-content">
    <!-- Seção de Recursos -->
    <section id="features" class="section py-5" style="background: #fdfaf3;">
      <div class="container">
        <div class="row justify-content-center text-center mb-5 mt-4">
          <div class="col-lg-8">
            <h2 class="section-title text-verde">{{ features.title }}</h2>
            <p class="section-subtitle mx-auto">{{ features.subtitle }}</p>
          </div>
        </div>
        
        <div class="row g-4">
          <article 
            class="col-md-4" 
            v-for="(servico, index) in servicos" 
            :key="servico.id || index"
          >
            <div 
              class="service-card animate-on-scroll" 
              :style="{ animationDelay: `${index * 0.1}s` }"
            >
              <div class="service-image-container">
                <div 
                  class="service-image" 
                  :style="{ backgroundImage: `url(${getServicoImage(servico, index)})` }"
                ></div>

                <!-- Carousel Controls -->
                <div v-if="servico.foto && servico.foto.length > 1" class="service-carousel-controls">
                  <button @click.prevent="prevServicoImage(servico, index)" class="carousel-btn"><i class="bi bi-chevron-left"></i></button>
                  <button @click.prevent="nextServicoImage(servico, index)" class="carousel-btn"><i class="bi bi-chevron-right"></i></button>
                </div>
                
                <!-- Carousel Indicators -->
                <div v-if="servico.foto && servico.foto.length > 1" class="service-carousel-indicators">
                  <span 
                    v-for="(_, imgIdx) in servico.foto" 
                    :key="imgIdx"
                    class="dot"
                    :class="{ active: (activeImageIndices[index] || 0) === imgIdx }"
                    @click.prevent="setServicoImage(index, imgIdx)"
                  ></span>
                </div>

                <div class="price-badge" v-if="servico.preco && servico.preco !== '0'">
                  <span class="currency">R$</span>
                  <span class="value">{{ servico.preco }}</span>
                  <span class="unit">/m²</span>
                </div>
              </div>

              <div class="service-content">
                <div class="d-flex align-items-center mb-3">
                  <div class="service-icon-box flex-shrink-0 me-3">
                    {{ ['🎨', '🏠', '🏢', '✨', '🖌️'][index % 5] }}
                  </div>
                  <h5 class="fw-bold text-verde mb-0">{{ servico.nome }}</h5>
                </div>
                <p class="service-description text-muted mb-4">{{ servico.detalhe }}</p>
                
                <div class="service-footer mt-auto">
                  <a href="#simulador" class="btn-service-outline">Solicitar Orçamento</a>
                </div>
              </div>
            </div>
          </article>

          <div v-if="servicos.length === 0" class="col-12 text-center text-muted py-5">
            <div class="spinner-border text-success" role="status">
              <span class="visually-hidden">Carregando serviços...</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Seção Sobre (Glass Card Simplificado) -->
    <section id="about" class="about-section position-relative overflow-hidden">
      <!-- Background Overlay -->
      <div class="about-bg-container">
        <div class="about-bg-image"></div>
        <div class="about-overlay"></div>
      </div>
      
      <div class="container py-5 position-relative" style="z-index: 5;">
        <div class="row justify-content-center">
          <div class="col-lg-10">
            <div class="glass-card-main animate-on-scroll">
              <div class="text-center mb-5">
                <span class="badge-premium mb-3">Nossa História</span>
                <h2 class="display-4 fw-800 text-crema mb-4">{{ about.title }}</h2>
                <div class="divider-gold mx-auto mb-4"></div>
              </div>

              <div class="row g-4 align-items-center">
                <div class="col-lg-7">
                  <div class="about-text-content pe-lg-4">
                    <p class="lead text-crema opacity-90 mb-4 fw-500">{{ about.description1 }}</p>
                    <p class="text-salvia mb-5">{{ about.description2 }}</p>
                    
                    <div class="d-flex flex-wrap gap-4 mb-5">
                      <div class="stat-pill">
                        <span class="stat-number">12+</span>
                        <span class="stat-label text-salvia">Anos de Experiência</span>
                      </div>
                      <div class="stat-pill">
                        <span class="stat-number">800+</span>
                        <span class="stat-label text-salvia">Obras Entregues</span>
                      </div>
                      <div class="stat-pill">
                        <span class="stat-number">100%</span>
                        <span class="stat-label text-salvia">Satisfação</span>
                      </div>
                    </div>

                    <a href="#contact" class="btn-main-gold">
                      {{ about.ctaText }}
                      <i class="bi bi-whatsapp ms-2"></i>
                    </a>
                  </div>
                </div>

                <div class="col-lg-5">
                  <div class="floating-values-card glass-card">
                    <ul class="list-unstyled mb-0">
                      <li class="mb-3 d-flex align-items-center gap-3">
                        <div class="icon-box-small"><i class="bi bi-star-fill"></i></div>
                        <div>
                          <h6 class="mb-0 text-crema">Qualidade Premium</h6>
                          <small class="text-salvia">Foco total no acabamento</small>
                        </div>
                      </li>
                      <li class="mb-3 d-flex align-items-center gap-3">
                        <div class="icon-box-small"><i class="bi bi-clock-fill"></i></div>
                        <div>
                          <h6 class="mb-0 text-crema">Pontualidade</h6>
                          <small class="text-salvia">Prazos rigorosamente respeitados</small>
                        </div>
                      </li>
                      <li class="d-flex align-items-center gap-3">
                        <div class="icon-box-small"><i class="bi bi-shield-check"></i></div>
                        <div>
                          <h6 class="mb-0 text-crema">Garantia Real</h6>
                          <small class="text-salvia">Sua tranquilidade garantida</small>
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <NovoOrcamento id="simulador" :csrf-token="csrfToken" />
  </main>
</template>

<script setup>
import { ref, reactive, onMounted, onUnmounted, nextTick } from 'vue'
import NovoOrcamento from './NovoOrcamento.vue'

const props = defineProps({
  csrfToken: { type: String, default: '' },
  hero: { type: Object, default: () => ({}) },
  about: {
    type: Object,
    default: () => ({
      title: 'Sua obra em boas mãos',
      description1: 'A GR Pintura nasceu da paixão pelo acabamento perfeito. Com anos de experiência no mercado, somos referência em qualidade e confiança.',
      description2: 'Nossa equipe é treinada para entregar não apenas cor, mas proteção e renovação para o seu patrimônio, sempre respeitando prazos e orçamento.',
      ctaText: 'Solicitar via WhatsApp',
      imagePlaceholder: '[Foco em Qualidade e Acabamento Premium]'
    })
  },
})

// === Estado Reativo ===
const features = {
  title: 'Cores que inspiram, resultados que impressionam',
  subtitle: 'Renove a sensação de bem-estar em seu lar, garantimos elegância e proteção para o seu patrimônio.'
}
const observer = ref(null)
const scrollY = ref(0)

const heroImages = [
  '/04-gr-text-proj.jpg',
  '/4.jpg',
  '/5.jpg',
  '/text-background.png'
]
const currentSlide = ref(0)
const slideInterval = ref(null)
const servicos = ref([])

// === Métodos ===
const updateScroll = () => {
  scrollY.value = window.scrollY
}

const nextSlide = () => {
  currentSlide.value = (currentSlide.value + 1) % heroImages.length
}

// === Métodos: Carousel de Serviços ===
const activeImageIndices = reactive({})

const getServicoImage = (servico, index) => {
  const activeIdx = activeImageIndices[index] || 0
  if (servico.foto && servico.foto.length > activeIdx) {
    return '/uploads/' + servico.foto[activeIdx]
  }
  return 'https://images.unsplash.com/photo-1562664377-709f2c337eb2?auto=format&fit=crop&w=600&q=80'
}

const nextServicoImage = (servico, index) => {
  if (!servico.foto || servico.foto.length <= 1) return
  const current = activeImageIndices[index] || 0
  activeImageIndices[index] = (current + 1) % servico.foto.length
}

const prevServicoImage = (servico, index) => {
  if (!servico.foto || servico.foto.length <= 1) return
  const current = activeImageIndices[index] || 0
  activeImageIndices[index] = (current - 1 + servico.foto.length) % servico.foto.length
}

const setServicoImage = (index, imgIdx) => {
  activeImageIndices[index] = imgIdx
}

const initScrollAnimations = () => {
  observer.value = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('fade-in-up')
        observer.value?.unobserve(entry.target)
      }
    })
  }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' })

  nextTick(() => {
    document.querySelectorAll('.animate-on-scroll').forEach(el => observer.value?.observe(el))
  })
}

onMounted(async () => {
  initScrollAnimations()
  window.addEventListener('scroll', updateScroll)
  slideInterval.value = setInterval(nextSlide, 6000)

  try {
    const response = await fetch('/servico/api')
    if (response.ok) {
      servicos.value = await response.json()
      nextTick(() => {
        document.querySelectorAll('.animate-on-scroll').forEach(el => observer.value?.observe(el))
      })
    }
  } catch (error) {
    console.error('Erro ao buscar serviços:', error)
  }
})

onUnmounted(() => {
  if (slideInterval.value) clearInterval(slideInterval.value)
  window.removeEventListener('scroll', updateScroll)
})
</script>

<style scoped>
.hero {
  min-height: 100vh;
  display: flex;
  align-items: center;
  position: relative;
  overflow: hidden;
  background-color: #1f3b2f;
  padding-top: 110px; /* Offset para a navbar fixa */
}

.hero-bg-container {
  position: absolute;
  inset: 0;
  z-index: 1;
}

.hero-slide {
  position: absolute;
  inset: 0;
  background-size: cover;
  background-position: center;
  transition: opacity 1.5s ease-in-out;
  opacity: 0;
}

.hero-slide.active {
  opacity: 1;
}

.hero-overlay {
  position: absolute;
  inset: 0;
  background: 
    linear-gradient(135deg, rgba(40, 75, 59, 0.3) 0%, rgba(27, 51, 40, 0.7) 25%, rgba(27, 51, 40, 0.3) 50%),
    radial-gradient(circle at 20% 80%, rgba(176, 140, 87, 0.2), transparent 75%);
  z-index: 2;
}

.glass-card {
  background: rgba(40, 75, 59, 0.034);
  backdrop-filter: blur(14px);
  -webkit-backdrop-filter: blur(14px);
  border: 1px solid rgba(176, 141, 87, 0.25);
  border-radius: 18px;
  padding: 45px;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.35);
  position: relative;
  z-index: 2;
}

.fw-800 { font-weight: 800; }
.text-crema { color: #F2E8CF; }
.text-salvia { color: #A8B5A8; }
.text-verde { color: #284B3B; }
.bg-verde-floresta { background: #284B3B; }

.offer-badge {
  background: #B08D57;
  color: white;
  padding: 6px 18px;
  border-radius: 30px;
  font-weight: 600;
  font-size: .9rem;
  display: inline-block;
}

.btn-main {
  background: #B08D57;
  color: white;
  border: none;
  padding: 15px 36px;
  border-radius: 12px;
  font-weight: 700;
  transition: .2s;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.btn-main:hover {
  background: #c19b62;
  transform: translateY(-2px);
  color: white;
}

.btn-outline-crema {
  border: 2px solid #F2E8CF;
  color: #F2E8CF;
  padding: 15px 36px;
  border-radius: 12px;
  font-weight: 600;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.btn-outline-crema:hover {
  background: #F2E8CF;
  color: #284B3B;
}

.service-card {
  background: #ffffff;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
  transition: all 0.4s ease;
  height: 100%;
  display: flex;
  flex-direction: column;
  border: 1px solid rgba(176, 141, 87, 0.1);
}

.service-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 20px 40px rgba(40, 75, 59, 0.12);
  border-color: rgba(176, 141, 87, 0.3);
}

.service-image-container {
  position: relative;
  height: 220px;
  overflow: hidden;
}

.service-image {
  width: 100%;
  height: 100%;
  background-size: cover;
  background-position: center;
  transition: transform 0.6s ease;
}

.service-card:hover .service-image {
  transform: scale(1.05);
}

.service-carousel-controls {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 10px;
  opacity: 0;
  transition: opacity 0.3s ease;
  pointer-events: none;
}

.service-card:hover .service-carousel-controls {
  opacity: 1;
}

.carousel-btn {
  background: rgba(27, 51, 40, 0.6);
  color: white;
  border: none;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
  pointer-events: auto;
  transition: all 0.2s;
  backdrop-filter: blur(4px);
  -webkit-backdrop-filter: blur(4px);
}

.carousel-btn:hover {
  background: #B08D57;
  transform: scale(1.1);
}

.service-carousel-indicators {
  position: absolute;
  bottom: 12px;
  left: 0;
  width: 100%;
  display: flex;
  justify-content: center;
  gap: 6px;
  z-index: 2;
  pointer-events: none;
}

.service-carousel-indicators .dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.4);
  cursor: pointer;
  pointer-events: auto;
  transition: all 0.3s ease;
  box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.service-carousel-indicators .dot.active {
  background: #B08D57;
  width: 16px;
  border-radius: 4px;
}

.price-badge {
  position: absolute;
  bottom: 0;
  right: 0;
  background: #B08D57;
  color: #fff;
  padding: 8px 16px;
  border-radius: 14px 0 0 0;
  font-weight: 700;
  display: flex;
  align-items: baseline;
  gap: 3px;
  box-shadow: -5px -5px 15px rgba(0, 0, 0, 0.1);
}

.price-badge .currency { font-size: 0.9rem; opacity: 0.9; }
.price-badge .value { font-size: 1.4rem; line-height: 1; }
.price-badge .unit { font-size: 0.75rem; font-weight: 500; opacity: 0.8; }

.service-content {
  padding: 24px;
  display: flex;
  flex-direction: column;
  flex-grow: 1;
}

.service-icon-box {
  width: 48px;
  height: 48px;
  background: rgba(176, 141, 87, 0.1);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  color: #B08D57;
}

.service-description {
  flex-grow: 1;
  font-size: 0.95rem;
  line-height: 1.6;
}

.btn-service-outline {
  display: block;
  width: 100%;
  text-align: center;
  padding: 12px;
  border: 1px solid #B08D57;
  color: #B08D57;
  border-radius: 10px;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.3s ease;
}

.btn-service-outline:hover {
  background: #B08D57;
  color: #ffffff;
}

.section-title {
  font-weight: 800;
  font-size: 2.5rem;
}

.section-subtitle {
  color: #8E8577;
  font-size: 1.1rem;
  max-width: 600px;
}

.grayscale { filter: grayscale(1); opacity: 0.5; }

/* Animações */
.animate-on-scroll {
  opacity: 0;
  transform: translateY(30px);
  transition: 0.8s ease-out;
}

.fade-in-up {
  opacity: 1;
  transform: translateY(0);
}

@media (max-width: 768px) {
  .hero {
    min-height: auto;
    padding-top: 140px;
    padding-bottom: 80px;
    text-align: center;
  }
  
  .glass-card { 
    padding: 24px 18px; 
    border-radius: 16px;
    margin: 0 auto;
    max-width: 92%;
  }
  
  .display-4 {
    font-size: 2rem !important;
    line-height: 1.2;
    margin-bottom: 0.75rem !important;
  }

  .offer-badge {
    padding: 4px 12px;
    font-size: 0.75rem;
    margin-bottom: 12px;
  }
  
  .section-title { 
    font-size: 1.8rem; 
  }

  .section-subtitle {
    font-size: 1rem;
    margin-bottom: 2rem;
  }

  .lead {
    font-size: 0.95rem;
    margin-bottom: 1.5rem !important;
  }

  .btn-main, .btn-outline-crema, .btn-main-gold { 
    width: 100%; 
    padding: 14px 20px;
    font-size: 0.95rem;
    justify-content: center;
  }

  .cta-form .input-group {
    flex-direction: column;
    gap: 10px;
    background: transparent !important;
  }

  .cta-form .form-control {
    border-radius: 12px !important;
    width: 100%;
  }

  .cta-form .btn-main {
    border-radius: 12px !important;
    width: 100%;
  }

  /* Responsividade Features/Services */
  .service-image-container {
    height: 180px;
  }

  .service-content {
    padding: 20px;
  }

  .service-icon-box {
    width: 44px;
    height: 44px;
    font-size: 1.3rem;
  }

  /* Responsividade About */
  .about-section {
    padding: 60px 0;
  }

  .glass-card-main {
    padding: 30px 20px;
    border-radius: 24px;
  }

  .stat-pill {
    flex: 1 1 120px;
    text-align: center;
  }

  .stat-number {
    font-size: 1.6rem;
  }

  .divider-gold {
    width: 50px;
  }
}

/* Nova Seção Sobre Simplificada */
.about-section {
  min-height: 90vh;
  display: flex;
  align-items: center;
  padding: 100px 0;
  background: #1b3328;
}

.about-bg-container {
  position: absolute;
  inset: 0;
  z-index: 1;
}

.about-bg-image {
  position: absolute;
  inset: 0;
  background-image: url('https://images.unsplash.com/photo-1589939705384-5185138a04b9?auto=format&fit=crop&w=1920&q=80');
  background-size: cover;
  background-position: center;
  filter: saturate(0.5) brightness(0.6);
}

.about-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(27, 51, 40, 0.95) 0%, rgba(27, 51, 40, 0.7) 100%);
}

.glass-card-main {
  background: rgba(40, 75, 59, 0.4);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid rgba(176, 141, 87, 0.3);
  border-radius: 40px;
  padding: 60px;
  box-shadow: 0 40px 100px rgba(0, 0, 0, 0.5);
}

.badge-premium {
  background: rgba(176, 141, 87, 0.15);
  color: #B08D57;
  padding: 8px 24px;
  border-radius: 50px;
  font-weight: 700;
  font-size: 0.85rem;
  letter-spacing: 2px;
  text-transform: uppercase;
  border: 1px solid rgba(176, 141, 87, 0.3);
  display: inline-block;
}

.divider-gold {
  width: 80px;
  height: 4px;
  background: #B08D57;
  border-radius: 2px;
}

.stat-pill {
  display: flex;
  flex-direction: column;
}

.stat-number {
  font-size: 2rem;
  font-weight: 800;
  color: #B08D57;
  line-height: 1;
  margin-bottom: 5px;
}

.stat-label {
  font-size: 0.85rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.btn-main-gold {
  background: #B08D57;
  color: white;
  border: none;
  padding: 18px 45px;
  border-radius: 12px;
  font-weight: 700;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  transition: all 0.3s ease;
  box-shadow: 0 10px 20px rgba(176, 141, 87, 0.3);
}

.btn-main-gold:hover {
  background: #c19b62;
  transform: translateY(-3px);
  box-shadow: 0 15px 30px rgba(176, 141, 87, 0.4);
  color: white;
}

.icon-box-small {
  width: 40px;
  height: 40px;
  background: rgba(176, 141, 87, 0.1);
  border: 1px solid rgba(176, 141, 87, 0.2);
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #B08D57;
}

.floating-values-card {
  background: rgba(255, 255, 255, 0.05) !important;
  border: 1px solid rgba(255, 255, 255, 0.1) !important;
  padding: 30px !important;
}

.opacity-90 { opacity: 0.9; }

@media (max-width: 991px) {
  .glass-card-main {
    padding: 40px 30px;
    border-radius: 30px;
  }
  
  .stat-number {
    font-size: 1.5rem;
  }
}



</style>