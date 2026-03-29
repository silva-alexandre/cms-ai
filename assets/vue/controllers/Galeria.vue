<template>
  <div class="gallery-page">
    <!-- Header Section -->
    <header class="gallery-header position-relative overflow-hidden">
      <div class="header-bg"></div>
      <div class="header-overlay"></div>
      <div class="container position-relative z-2 py-5 mt-5">
        <div class="row justify-content-center text-center">
          <div class="col-lg-8">
            <span class="badge-premium mb-3 animate-up">Nosso Portfólio</span>
            <h1 class="display-3 fw-800 text-crema mb-4 animate-up" style="animation-delay: 0.1s">
              Galeria de Projetos
            </h1>
            <p class="lead text-salvia animate-up" style="animation-delay: 0.2s">
              Explore nossos trabalhos recentes e veja como transformamos ambientes com cores e acabamentos de alta qualidade.
            </p>
          </div>
        </div>
      </div>
    </header>

    <!-- Gallery Grid -->
    <section class="gallery-section py-5">
      <div class="container">
        <div class="row g-4">
          <div 
            v-for="(project, index) in projects" 
            :key="project.id"
            class="col-md-6 col-lg-4"
          >
            <div 
              class="project-card animate-on-scroll" 
              @click="openLightbox(project)"
              :style="{ animationDelay: `${index * 0.1}s` }"
            >
              <div class="project-img-wrapper">
                <img :src="project.image" :alt="project.title" class="project-img">
                <div class="project-overlay">
                  <div class="project-info">
                    <span class="category-tag">{{ project.category }}</span>
                    <h3 class="h4 text-white mb-0">{{ project.title }}</h3>
                    <div class="view-details mt-3">
                      <i class="bi bi-plus-lg"></i> Ver Detalhes
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Lightbox Modal -->
    <Transition name="fade">
      <div v-if="selectedProject" class="lightbox-overlay" @click.self="closeLightbox">
        <div class="lightbox-container glass-card">
          <button class="btn-close-lightbox" @click="closeLightbox">
            <i class="bi bi-x-lg"></i>
          </button>
          
          <div class="row g-0 h-100">
            <!-- Project Image -->
            <div class="col-lg-8 h-100 position-relative bg-dark d-flex align-items-center justify-content-center overflow-hidden">
              <img :src="selectedProject.image" :alt="selectedProject.title" class="lightbox-img img-fluid">
            </div>
            
            <!-- Project Details Sidebar -->
            <div class="col-lg-4 h-100 lightbox-details p-4 p-md-5 overflow-auto">
              <span class="badge-premium mb-3">{{ selectedProject.category }}</span>
              <h2 class="display-6 fw-800 text-crema mb-4">{{ selectedProject.title }}</h2>
              
              <div class="divider-gold mb-4"></div>
              
              <p class="text-salvia mb-5 lead">
                {{ selectedProject.description }}
              </p>
              
              <div class="project-stats mb-5">
                <div class="stat-item d-flex align-items-center mb-3">
                  <div class="stat-icon me-3"><i class="bi bi-calendar-check"></i></div>
                  <div>
                    <h6 class="mb-0 text-crema">Tempo de Obra</h6>
                    <small class="text-salvia">{{ selectedProject.duration }}</small>
                  </div>
                </div>
                <div class="stat-item d-flex align-items-center mb-3">
                  <div class="stat-icon me-3"><i class="bi bi-geo-alt"></i></div>
                  <div>
                    <h6 class="mb-0 text-crema">Localização</h6>
                    <small class="text-salvia">{{ selectedProject.location }}</small>
                  </div>
                </div>
                <div class="stat-item d-flex align-items-center">
                  <div class="stat-icon me-3"><i class="bi bi-palette"></i></div>
                  <div>
                    <h6 class="mb-0 text-crema">Cores Utilizadas</h6>
                    <small class="text-salvia">{{ selectedProject.colors }}</small>
                  </div>
                </div>
              </div>
              
              <a href="/#simulador" class="btn-main-gold w-100" @click="closeLightbox">
                Solicitar orçamento similar
                <i class="bi bi-whatsapp ms-2"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue'

const selectedProject = ref(null)

const projects = [
  {
    id: 1,
    title: 'Residência Granja Viana',
    category: 'Pintura Residencial',
    image: 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
    description: 'Neste projeto, realizamos a pintura completa interna e externa da residência, utilizando tintas premium com acabamento acetinado para maior durabilidade e sofisticação.',
    duration: '15 dias',
    location: 'Cotia, SP',
    colors: 'Off-white, Cinza Alpino, Madeira Natural'
  },
  {
    id: 2,
    title: 'Escritório Tech Hub',
    category: 'Pintura Comercial',
    image: 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80',
    description: 'Revitalização de espaço comercial corporativo. Foco em cores que estimulam a criatividade e produtividade, além de tratamento de paredes com gesso liso.',
    duration: '10 dias',
    location: 'Barueri, SP',
    colors: 'Azul Petróleo, Cinza Urbano, Branco Neve'
  },
  {
    id: 3,
    title: 'Apartamento Jardins',
    category: 'Fino Acabamento',
    image: 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1200&q=80',
    description: 'Trabalho minucioso de restauração de molduras de gesso e pintura com efeito velvet nas paredes da sala principal, elevando o padrão estético do imóvel.',
    duration: '7 dias',
    location: 'São Paulo, SP',
    colors: 'Pérola, Ouro Velho (detalhes), Areia'
  },
  {
    id: 4,
    title: 'Fachada Condomínio Vila Verde',
    category: 'Pintura Predial',
    image: 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=1200&q=80',
    description: 'Pintura externa de condomínio residencial com foco em proteção contra intempéries e revitalização das cores originais com tecnologia hidrorrepelente.',
    duration: '30 dias',
    location: 'Cotia, SP',
    colors: 'Gelatina de Uva, Branco Gelo, Grafite'
  },
  {
    id: 5,
    title: 'Área Gourmet Alphaville',
    category: 'Áreas Externas',
    image: 'https://images.unsplash.com/photo-1560185007-cde436f6a4d0?auto=format&fit=crop&w=1200&q=80',
    description: 'Aplicação de verniz premium em decks de madeira e pintura de alvenaria com textura rústica para compor um ambiente de lazer aconchegante.',
    duration: '5 dias',
    location: 'Santana de Parnaíba, SP',
    colors: 'Tijolo, Mogno, Cinza Bruma'
  },
  {
    id: 6,
    title: 'Clínica Odontológica Prime',
    category: 'Comercial / Saúde',
    image: 'https://images.unsplash.com/photo-1629909613654-28e377c37b09?auto=format&fit=crop&w=1200&q=80',
    description: 'Pintura especializada com tintas antibacterianas e acabamento lavável, ideal para ambientes de saúde, unindo estética e higiene funcional.',
    duration: '8 dias',
    location: 'Osasco, SP',
    colors: 'Branco Hospitalar, Azul Sereno'
  }
]

const openLightbox = (project) => {
  selectedProject.value = project
  document.body.style.overflow = 'hidden'
}

const closeLightbox = () => {
  selectedProject.value = null
  document.body.style.overflow = ''
}

// Scroll animation logic
const observer = ref(null)

const initScrollAnimations = () => {
  observer.value = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('fade-in-up')
        observer.value?.unobserve(entry.target)
      }
    })
  }, { threshold: 0.1 })

  nextTick(() => {
    document.querySelectorAll('.animate-on-scroll').forEach(el => observer.value?.observe(el))
  })
}

onMounted(() => {
  initScrollAnimations()
})
</script>

<style scoped>
.gallery-page {
  background-color: #fdfaf3;
  min-height: 100vh;
}

/* Header Styles */
.gallery-header {
  padding: 100px 0 60px;
  background-color: #1b3328;
}

.header-bg {
  position: absolute;
  inset: 0;
  background-image: url('https://images.unsplash.com/photo-1589939705384-5185138a04b9?auto=format&fit=crop&w=1920&q=80');
  background-size: cover;
  background-position: center;
  filter: saturate(0.5) brightness(0.4);
}

.header-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(to bottom, transparent, rgba(27, 51, 40, 0.9));
}

.z-2 { z-index: 2; }

/* Grid Styles */
.project-card {
  cursor: pointer;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
}

.project-img-wrapper {
  position: relative;
  aspect-ratio: 4/3;
  overflow: hidden;
}

.project-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.8s ease;
}

.project-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, rgba(27, 51, 40, 0.95) 0%, rgba(27, 51, 40, 0.4) 50%, rgba(27, 51, 40, 0.1) 100%);
  display: flex;
  align-items: flex-end;
  padding: 25px;
  opacity: 1; /* Always visible by default */
  transition: background 0.4s ease;
}

.project-info {
  transform: translateY(0); /* Start at final position */
  transition: transform 0.4s ease;
  width: 100%;
}

.category-tag {
  background: #B08D57;
  color: white;
  padding: 4px 12px;
  border-radius: 50px;
  font-size: 0.7rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1px;
  display: inline-block;
  margin-bottom: 8px;
}

.view-details {
  color: #F2E8CF;
  font-weight: 600;
  font-size: 0.85rem;
  display: flex;
  align-items: center;
  gap: 8px;
  opacity: 0.8;
  margin-top: 12px;
}

.project-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
}

.project-card:hover .project-img {
  transform: scale(1.05);
}

.project-card:hover .project-overlay {
  background: linear-gradient(to top, rgba(176, 141, 87, 0.9) 0%, rgba(27, 51, 40, 0.6) 100%);
}

.project-card:hover .view-details {
  opacity: 1;
}

/* Lightbox Styles */
.lightbox-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.9);
  backdrop-filter: blur(5px);
  z-index: 2000;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
}

.lightbox-container {
  width: 100%;
  max-width: 1200px;
  height: 90vh;
  background: #1b3328 !important;
  border: 1px solid rgba(176, 141, 87, 0.3);
  border-radius: 30px;
  overflow: hidden;
  position: relative;
  box-shadow: 0 50px 100px rgba(0, 0, 0, 0.6) !important;
}

.btn-close-lightbox {
  position: absolute;
  top: 20px;
  right: 20px;
  background: rgba(255, 255, 255, 0.1);
  border: none;
  color: white;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 10;
  transition: all 0.2s;
}

.btn-close-lightbox:hover {
  background: #B08D57;
  transform: rotate(90deg);
}

.lightbox-img {
  max-height: 100%;
  width: 100%;
  object-fit: contain;
}

.lightbox-details {
  background: rgba(40, 75, 59, 0.3);
  backdrop-filter: blur(10px);
  border-left: 1px solid rgba(176, 141, 87, 0.2);
}

/* Common Components (Consistent with Home.vue) */
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

.fw-800 { font-weight: 800; }
.text-crema { color: #F2E8CF; }
.text-salvia { color: #A8B5A8; }

.divider-gold {
  width: 60px;
  height: 3px;
  background: #B08D57;
  border-radius: 2px;
}

.stat-icon {
  width: 40px;
  height: 40px;
  background: rgba(176, 141, 87, 0.15);
  border: 1px solid rgba(176, 141, 87, 0.3);
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #B08D57;
  flex-shrink: 0;
}

.btn-main-gold {
  background: #B08D57;
  color: white;
  border: none;
  padding: 18px 30px;
  border-radius: 12px;
  font-weight: 700;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  box-shadow: 0 10px 20px rgba(176, 141, 87, 0.3);
}

.btn-main-gold:hover {
  background: #c19b62;
  transform: translateY(-3px);
  box-shadow: 0 15px 30px rgba(176, 141, 87, 0.4);
  color: white;
}

/* Animations */
.animate-up {
  opacity: 0;
  transform: translateY(20px);
  animation: slideUp 0.8s ease forwards;
}

@keyframes slideUp {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-on-scroll {
  opacity: 0;
  transform: translateY(30px);
  transition: 0.8s ease-out;
}

.fade-in-up {
  opacity: 1;
  transform: translateY(0);
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Responsiveness */
@media (max-width: 991px) {
  .lightbox-container {
    height: auto;
    max-height: 95vh;
    overflow-y: auto;
  }
  
  .lightbox-details {
    border-left: none;
    border-top: 1px solid rgba(176, 141, 87, 0.2);
  }
  
  .gallery-header {
    padding-top: 120px;
  }
}

@media (max-width: 768px) {
  .gallery-header {
    padding-top: 100px;
    padding-bottom: 40px;
  }
  
  .display-3 {
    font-size: 2.2rem;
  }
  
  .project-overlay {
    padding: 15px;
  }
  
  .lightbox-details {
    padding: 20px !important;
  }
  
  .btn-main-gold {
     width: 100%;
     padding: 14px 20px;
  }
  
  .stat-item {
    flex-direction: column;
    text-align: center;
  }
  
  .stat-icon {
    margin-right: 0 !important;
    margin-bottom: 10px;
  }
}

@media (max-width: 425px) {
  .gallery-header {
    padding-top: 90px;
    padding-bottom: 30px;
  }
  .display-3 {
    font-size: 1.8rem;
  }
  .lead {
    font-size: 1rem;
  }
  .project-overlay {
    padding: 10px;
  }
  .h4 { font-size: 1.1rem; }
  .view-details { font-size: 0.8rem; }
  .lightbox-details { padding: 15px !important; }
  .btn-main-gold { font-size: 0.9rem; padding: 12px 15px; }
  .lightbox-img {
    max-height: 30vh;
  }
}
</style>
