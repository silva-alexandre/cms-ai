<template>
  <section class="section py-5" id="simulador-orcamento">
    <div class="container">
      <div class="text-center mb-5 animate-on-scroll">
        <span class="badge-premium mb-3">Calculadora Proativa</span>
        <h2 class="display-5 fw-800 text-crema mb-3">{{ title }}</h2>
        <div class="divider-gold mx-auto mb-4"></div>
        <p class="section-subtitle mx-auto text-salvia opacity-90 text-center">{{ subtitle }}</p>
      </div>

      <div v-if="isLoading" class="text-center my-5 py-5 animate-on-scroll">
        <div class="spinner-border text-bronze" role="status" style="width: 3rem; height: 3rem;">
          <span class="visually-hidden">Carregando...</span>
        </div>
      </div>

      <div v-else-if="fetchError" class="alert alert-danger glass-card text-center my-5 animate-on-scroll">
        {{ fetchError }}
      </div>

      <template v-else>
        <!-- Navbar minimalista de Serviços -->
        <nav class="servicos-nav mb-5 animate-on-scroll">
          <div class="nav-wrapper d-flex justify-content-center gap-2 flex-wrap">
            <button
              type="button"
              v-for="service in displayedServices"
              :key="service.id"
              :class="['nav-item-btn', { active: service.id === selectedServiceId }]"
              @click="selectService(service)"
            >
              {{ service.nome }}
            </button>
          </div>
        </nav>

        <!-- Painel de detalhes unificado -->
        <component :is="isEmbedded ? 'div' : 'form'" @submit.prevent="handleSubmit" class="servico-panel glass-card-main animate-on-scroll" v-if="selectedService">
          <div class="row g-0">
            <!-- Coluna do Carousel -->
            <div class="col-lg-6">
              <div class="carousel-wrapper position-relative h-100 min-vh-40">
                <div class="carousel-inner h-100 overflow-hidden">
                  <transition name="fade" mode="out-in">
                    <img
                      :key="currentImageIndex"
                      :src="currentImageUrl"
                      class="img-fluid carousel-image"
                      :alt="selectedService.nome"
                      @load="imageLoading = false"
                    />
                  </transition>
                </div>

                <!-- Overlay de Carregar -->
                <div v-if="imageLoading" class="carousel-loader">
                   <div class="spinner-border spinner-border-sm text-bronze"></div>
                </div>

                <!-- Controles do Carousel -->
                <div class="carousel-controls" v-if="hasMultipleImages">
                  <button type="button" class="carousel-control prev" @click="prevImage">
                    <i class="bi bi-chevron-left"></i>
                  </button>
                  <button type="button" class="carousel-control next" @click="nextImage">
                    <i class="bi bi-chevron-right"></i>
                  </button>
                </div>

                <!-- Indicadores -->
                <div class="carousel-indicators" v-if="hasMultipleImages">
                  <span
                    v-for="(_, index) in selectedService.foto"
                    :key="index"
                    :class="['indicator', { active: index === currentImageIndex }]"
                    @click="goToImage(index)"
                  ></span>
                </div>
              </div>
            </div>

            <!-- Coluna de Info e Calculadora -->
            <div class="col-lg-6">
              <div class="servico-info p-4 p-md-5">
                <h3 class="detail-title text-crema fw-800 mb-3">{{ selectedService.nome }}</h3>
                <p class="detail-text text-salvia mb-4">{{ selectedService.descricao || 'Acabamento de alta qualidade para valorizar seu imóvel.' }}</p>

                <div v-if="isEmbedded" class="mb-4">
                  <label for="titulo-input" class="form-label text-salvia small opacity-75">Título do Orçamento</label>
                  <div class="input-wrap">
                    <input 
                      id="titulo-input" 
                      v-model="titulo" 
                      type="text" 
                      class="custom-input-mini" 
                      placeholder="Ex: Pintura da Fachada Norte"
                    >
                    <div class="input-icon-mini">📝</div>
                  </div>
                </div>

                <div class="divider-gold opacity-25 w-100 mb-4" style="height: 1px;"></div>

                <div class="calculator-wrapper">
                  <div class="mb-4">
                    <label class="form-label text-salvia small opacity-75 d-flex justify-content-between">
                      <span>Preço por m²</span>
                      <span class="text-crema">{{ formatCurrency(selectedService.preco) }}</span>
                    </label>
                  </div>

                  <div class="mb-4">
                    <label for="quantity-range" class="form-label d-flex justify-content-between">
                      <span class="text-salvia">Área Estimada</span>
                      <span class="quantity-badge text-bronze fw-bold h4 mb-0">{{ quantity }} m²</span>
                    </label>
                    <input 
                      type="range" 
                      id="quantity-range" 
                      class="form-range custom-range" 
                      v-model.number="quantity" 
                      min="1" 
                      max="500" 
                      step="1"
                      @input="calculateTotal"
                    >
                    <div class="range-labels mt-2 d-flex justify-content-between text-salvia opacity-50 small">
                      <span>1 m²</span>
                      <span>500 m²</span>
                    </div>
                  </div>

                  <div class="result-panel-mini floating-values-card glass-card text-center p-4 mb-4">
                    <span class="total-label text-salvia small opacity-75">Investimento Estimado</span>
                    <h2 class="total-value text-bronze fw-800 display-5 mt-2 mb-0">{{ formatCurrency(totalValue) }}</h2>
                  </div>

                  <!-- Hidden Inputs for Symfony Form Persistence -->
                  <div v-if="isEmbedded" style="display:none">
                    <input type="hidden" :name="fieldNames.titulo" :value="titulo">
                    <input type="hidden" :name="fieldNames.selectedServiceId" :value="selectedServiceId">
                    <input type="hidden" :name="fieldNames.quantity" :value="quantity">
                    <input type="hidden" :name="fieldNames.totalValue" :value="totalValue">
                  </div>

                  <!-- Honeypot field for bot protection -->
                  <div class="hp-field" style="display:none !important;" aria-hidden="true">
                    <input type="text" v-model="hp_email" tabindex="-1" autocomplete="off">
                  </div>

                  <button 
                    v-if="!isEmbedded"
                    class="btn-main-gold w-100 py-3" 
                    :disabled="isSubmitting"
                  >
                    <template v-if="isSubmitting">
                      <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                      Enviando...
                    </template>
                    <template v-else>
                      <i class="bi bi-whatsapp me-2"></i> Solicitar Orçamento via WhatsApp
                    </template>
                  </button>
                  <p v-if="!isEmbedded" class="mt-3 text-center small text-salvia opacity-50 mb-0">
                    * Sugestão de valores para agilizar o atendimento.
                  </p>
                  <p v-else class="mt-1 text-center small text-salvia opacity-50 mb-0">
                    * Os valores calculados serão salvos no banco de dados.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </component>
      </template>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'

const props = defineProps({
  services: {
    type: Array,
    default: () => []
  },
  title: {
    type: String,
    default: 'Simule sua Renovação'
  },
  subtitle: {
    type: String,
    default: 'A cor certa valoriza seu patrimônio. Descubra o valor do seu projeto.'
  },
  isEmbedded: { type: Boolean, default: false },
  csrfToken: { type: String, default: '' },
  initialData: { type: Object, default: () => ({}) },
  fieldNames: { type: Object, default: () => ({}) }
})

const emit = defineEmits(['request-quote'])

// === Estado Reativo ===
const fetchedServices = ref([])
const isLoading = ref(false)
const fetchError = ref(null)
const imageLoading = ref(false)
const currentImageIndex = ref(0)

const titulo = ref(props.initialData?.titulo || '')
const selectedServiceId = ref(props.initialData?.id_servico ? parseInt(props.initialData.id_servico) : null)
const quantity = ref(props.initialData?.qtd ? parseFloat(props.initialData.qtd) : 20)
const totalValue = ref(0)
const isSubmitting = ref(false)
const hp_email = ref('') // Honeypot field

// === Computed ===
const displayedServices = computed(() => {
  return fetchedServices.value.length > 0 ? fetchedServices.value : (props.services.length > 0 ? props.services : [])
})

const selectedService = computed(() => {
  return displayedServices.value.find(s => s.id === selectedServiceId.value)
})

const currentImageUrl = computed(() => {
  if (selectedService.value?.foto?.[currentImageIndex.value]) {
    return '/uploads/' + selectedService.value.foto[currentImageIndex.value]
  }
  return 'https://images.unsplash.com/photo-1562664377-709f2c337eb2?auto=format&fit=crop&w=800&q=80'
})

const hasMultipleImages = computed(() => {
  return selectedService.value?.foto?.length > 1
})

// === Métodos ===
const loadServices = async () => {
  isLoading.value = true
  fetchError.value = null
  try {
    const response = await fetch('/servico/api')
    if (!response.ok) throw new Error('Falha ao conectar com o servidor')
    const data = await response.json()
    fetchedServices.value = data
    
    // Auto-selecionar o primeiro serviço se houver
    if (data.length > 0 && !selectedServiceId.value) {
      selectedServiceId.value = data[0].id
    }
  } catch (err) {
    console.warn('API offline, tentando usar fallback.', err)
    if (props.services.length > 0) {
      selectedServiceId.value = props.services[0].id
    } else {
      fetchError.value = "Não foi possível carregar os serviços."
    }
  } finally {
    isLoading.value = false
  }
}

const selectService = (service) => {
  selectedServiceId.value = service.id
  currentImageIndex.value = 0
  calculateTotal()
}

const calculateTotal = () => {
  if (selectedService.value) {
    totalValue.value = parseFloat(selectedService.value.preco) * quantity.value
  } else {
    totalValue.value = 0
  }
}

const formatCurrency = (value) => {
  return new Intl.NumberFormat('pt-BR', {
    style: 'currency',
    currency: 'BRL'
  }).format(value)
}

// Carousel Methods
const nextImage = () => {
  if (!selectedService.value?.foto) return
  imageLoading.value = true
  currentImageIndex.value = (currentImageIndex.value + 1) % selectedService.value.foto.length
}

const prevImage = () => {
  if (!selectedService.value?.foto) return
  imageLoading.value = true
  currentImageIndex.value = (currentImageIndex.value - 1 + selectedService.value.foto.length) % selectedService.value.foto.length
}

const goToImage = (index) => {
  imageLoading.value = true
  currentImageIndex.value = index
}

const handleSubmit = async () => {
  if (selectedService.value && !isSubmitting.value) {
    if (!props.isEmbedded) {
      isSubmitting.value = true
      try {
        const response = await fetch('/orcamento/api/create', {
          method: 'POST',
          headers: { 
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': props.csrfToken 
          },
          body: JSON.stringify({
            titulo: 'Solicitação via Home',
            qtd: quantity.value,
            id_servico: selectedService.value.id,
            hp_email: hp_email.value
          })
        })
        
        if (!response.ok) throw new Error('Erro ao registrar orçamento')
        
      } catch (err) {
        console.error('Falha ao persistir lead:', err)
      } finally {
        isSubmitting.value = false
      }
    }

    const message = `Olá, gostaria de um orçamento para ${selectedService.value.nome} com área de ${quantity.value}m². Total estimado: ${formatCurrency(totalValue.value)}`
    window.open(`https://wa.me/5511948780179?text=${encodeURIComponent(message)}`, '_blank')
  }
}

// Watchers
watch(selectedServiceId, () => {
  calculateTotal()
})

onMounted(() => {
  loadServices().then(() => calculateTotal())
})
</script>

<style scoped>
.glass-card-main {
  background: rgb(2, 85, 53);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid rgba(176, 141, 87, 0.3);
  border-radius: 40px;
  box-shadow: 0 40px 100px rgba(0, 0, 0, 0.5);
  overflow: hidden;
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

.nav-item-btn {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  color: #A8B5A8;
  padding: 10px 25px;
  border-radius: 50px;
  font-weight: 600;
  transition: all 0.3s ease;
}

.nav-item-btn:hover {
  background: rgba(176, 141, 87, 0.1);
  border-color: rgba(176, 141, 87, 0.3);
  color: #F2E8CF;
}

.nav-item-btn.active {
  background: #B08D57;
  color: white;
  border-color: #B08D57;
  box-shadow: 0 5px 15px rgba(176, 141, 87, 0.3);
}

.carousel-wrapper {
  background: rgba(0, 0, 0, 0.2);
}

.carousel-inner {
  display: flex;
  align-items: center;
  justify-content: center;
}

.carousel-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  min-height: 480px;
}

.carousel-controls {
  position: absolute;
  top: 50%;
  left: 0;
  right: 0;
  transform: translateY(-50%);
  display: flex;
  justify-content: space-between;
  padding: 0 20px;
  pointer-events: none;
}

.carousel-control {
  width: 45px;
  height: 45px;
  border-radius: 50%;
  background: rgba(0, 0, 0, 1);
  border: 1px solid rgba(176, 141, 87, 0.3);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  pointer-events: auto;
  transition: all 0.3s ease;
  backdrop-filter: blur(5px);
}

.carousel-control:hover {
  background: #B08D57;
  transform: scale(1.1);
}

.carousel-indicators {
  position: absolute;
  bottom: 25px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 10px;
}

.indicator {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.3);
  cursor: pointer;
  transition: all 0.3s ease;
}

.indicator.active {
  background: #B08D57;
  width: 30px;
  border-radius: 10px;
}

.floating-values-card {
  background: rgba(255, 255, 255, 0.05) !important;
  border: 1px solid rgba(255, 255, 255, 0.1) !important;
}

.custom-range {
  height: 8px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 6px;
  width: 100%;
}

.custom-range::-webkit-slider-thumb {
  background: #B08D57;
  box-shadow: 0 0 15px rgba(176, 141, 87, 0.6);
  cursor: pointer;
  border-radius: 50%;
  width: 24px;
  height: 24px;
  appearance: none;
  border: 2px solid white;
}

.btn-main-gold {
  background: #B08D57;
  color: white !important;
  border: none;
  padding: 18px 45px;
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
}

.text-crema { color: #F2E8CF; }
.text-salvia { color: #A8B5A8; }
.text-bronze { color: #B08D57; }
.fw-800 { font-weight: 800; }

.section-subtitle {
  max-width: 600px;
}

/* Transitions */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.4s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.carousel-loader {
  position: absolute;
  inset: 0;
  background: rgba(27, 51, 40, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 5;
}

@media (max-width: 991px) {
  .carousel-image {
    min-height: 300px;
    max-height: 400px;
  }
}

@media (max-width: 768px) {
  .glass-card-main {
    border-radius: 24px;
  }
  
  .display-5 {
    font-size: 1.8rem !important;
  }

  .servico-info {
    padding: 30px 20px !important;
  }
}

.custom-input-mini {
  background: rgba(255, 255, 255, 0.08) !important;
  border: 1px solid rgba(176, 141, 87, 0.2);
  color: #F2E8CF !important;
  padding: 0.8rem 1rem 0.8rem 3rem;
  border-radius: 10px;
  width: 100%;
}

.input-wrap {
  position: relative;
}

.input-icon-mini {
  position: absolute;
  left: 1rem;
  top: 50%;
  transform: translateY(-50%);
  opacity: 0.6;
}
</style>
