<template>
  <div class="orcamento-view-wrapper">
    <!-- Hero Background -->
    <div class="hero-bg d-flex align-items-center justify-content-center">
      <div class="text-center text-white z-2 position-relative px-3">
        <span class="badge-premium mb-3">#{{ orcamento.id }} - Orçamento</span>
        <h1 class="display-4 fw-800 mb-2">{{ orcamento.titulo }}</h1>
        <p class="lead opacity-75 mb-0">Confira os valores, informações de medição e datas associadas.</p>
      </div>
      <div class="hero-overlay"></div>
    </div>

    <!-- Main Content Container (Overlapping the hero) -->
    <div class="container main-content">
      <div class="row justify-content-center">
        <div class="col-lg-10">
          
          <!-- Actions Header -->
          <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
              <h2 class="section-title text-verde mb-0">Resumo Financeiro</h2>
            </div>
            <div class="d-flex align-items-center gap-3">
              <a href="/orcamento/" class="btn-outline-main">
                <i class="bi bi-arrow-left me-2"></i> Voltar para Lista
              </a>
              <div class="action-buttons">
              <a :href="`/orcamento/${orcamento.id}/edit`" class="btn-icon edit" title="Editar Orçamento">
                <i class="bi bi-pencil"></i>
              </a>
              <form method="post" :action="deleteUrl" @submit="confirmDelete" style="margin: 0;">
                <input type="hidden" name="_token" :value="csrfToken">
                <button type="submit" class="btn-icon delete" title="Excluir Orçamento">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </div>
          </div>
          </div>

          <!-- Important Info -->
          <div class="glass-card mb-4 mb-lg-5">
            <div class="info-grid">
              
              <!-- Título -->
              <div class="info-item full-width">
                <div class="d-flex align-items-center">
                  <div class="icon-wrap me-3">
                    <i class="bi bi-card-text text-verde fs-4"></i>
                  </div>
                  <div>
                    <label>Título / Referência</label>
                    <div class="info-value text-verde fw-bold">{{ orcamento.titulo }}</div>
                  </div>
                </div>
              </div>
              
              <!-- Qtd / Área -->
              <div class="info-item">
                <div class="d-flex align-items-center">
                  <div class="icon-wrap me-3">
                    <i class="bi bi-rulers text-verde fs-4"></i>
                  </div>
                  <div>
                    <label>Metragem / Área (m²)</label>
                    <div class="info-value">{{ orcamento.qtd }}</div>
                  </div>
                </div>
              </div>
              
              <!-- Data -->
              <div class="info-item">
                <div class="d-flex align-items-center">
                  <div class="icon-wrap me-3">
                    <i class="bi bi-calendar-check text-verde fs-4"></i>
                  </div>
                  <div>
                    <label>Data de Solicitação</label>
                    <div class="info-value">{{ formatDate(orcamento.data) }}</div>
                  </div>
                </div>
              </div>

            </div>
          </div>

          <!-- Highlight Panel -->
          <div class="valor-card highlight-card d-flex align-items-center justify-content-between flex-wrap gap-4">
            <div>
              <h3 class="highlight-title mb-1">Custo Estimado Total</h3>
              <p class="mb-0 highlight-subtitle">Com base na área solicitada e orçamento.</p>
            </div>
            <div class="valor-total-display">
              <span class="currency">R$</span> {{ formatMoney(orcamento.valorTotal) }}
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  orcamento: {
    type: Object,
    required: true,
    default: () => ({})
  },
  deleteUrl: {
    type: String,
    required: true
  },
  csrfToken: {
    type: String,
    required: true
  }
})

const formatMoney = (val) => {
  if (!val) return '0,00'
  return parseFloat(val).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

const formatDate = (dateString) => {
  if (!dateString) return 'Data não informada'
  const d = new Date(dateString)
  if (isNaN(d.getTime())) return dateString
  return new Intl.DateTimeFormat('pt-BR', {
    day: '2-digit', month: '2-digit', year: 'numeric',
    hour: '2-digit', minute: '2-digit'
  }).format(d)
}

const confirmDelete = (e) => {
  if (!confirm('Tem certeza de que deseja excluir este orçamento? Esta ação é irreversível.')) {
    e.preventDefault();
  }
}
</script>

<style scoped>
/* Resets the default padding-top from layout if implemented */
:global(body), :global(html) {
  margin: 0; padding: 0;
}

.orcamento-view-wrapper {
  background-color: #fdfaf3;
  padding-bottom: 80px;
}

/* --- HERO --- */
.hero-bg {
  position: relative;
  background-color: #1b3328;
  background-image: url('/public/3.png');
  background-size: cover;
  background-position: center;
  height: 500px;
  padding-top: 130px; /* Respect the transparent absolute navbar */
  width: 100%;
}
.hero-overlay {
  position: absolute;
  top: 0; left: 0; right: 0; bottom: 0;
  background: linear-gradient(135deg, rgba(27, 51, 40, 0.85) 0%, rgba(40, 75, 59, 0.75) 100%);
  z-index: 1;
}

.badge-premium {
  background: rgba(176, 141, 87, 0.2);
  color: #F2E8CF;
  padding: 8px 20px;
  border-radius: 50px;
  font-weight: 700;
  font-size: 0.85rem;
  letter-spacing: 1.5px;
  text-transform: uppercase;
  border: 1px solid rgba(176, 141, 87, 0.4);
  display: inline-block;
}

.fw-800 { font-weight: 800; }

/* --- MAIN CONTENT OVERLAP --- */
.main-content {
  margin-top: -100px; /* Overlaps hero */
  position: relative;
  z-index: 5;
}

.text-verde { color: #284B3B; }
.section-title { font-weight: 800; font-size: 2rem; }

/* --- GLASS CARD --- */
.glass-card {
  background: #ffffff;
  border-radius: 20px;
  padding: 35px;
  box-shadow: 0 15px 50px rgba(0, 0, 0, 0.08);
  border: 1px solid rgba(176, 141, 87, 0.15);
}

.info-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 25px;
}
.full-width { grid-column: 1 / -1; }

.info-item {
  background: #fdfaf3;
  padding: 20px 25px;
  border-radius: 15px;
  border: 1px solid rgba(176, 141, 87, 0.1);
  transition: all 0.3s ease;
}
.info-item:hover {
  background: white;
  box-shadow: 0 5px 15px rgba(0,0,0,0.04);
  transform: translateY(-2px);
}

.icon-wrap {
  width: 50px;
  height: 50px;
  border-radius: 12px;
  background: rgba(40, 75, 59, 0.05);
  display: flex;
  align-items: center;
  justify-content: center;
}

.info-item label {
  display: block;
  font-size: 0.8rem;
  color: #8E8577;
  font-weight: 800;
  margin-bottom: 5px;
  text-transform: uppercase;
  letter-spacing: 1px;
}
.info-value {
  font-size: 1.25rem;
  color: #333;
}


/* --- VALOR CARD HIGHLIGHT --- */
.highlight-card {
  background: linear-gradient(135deg, #1b3328 0%, #284B3B 100%);
  border-radius: 20px;
  padding: 40px;
  color: white;
  box-shadow: 0 20px 40px rgba(40, 75, 59, 0.3);
  position: relative;
  overflow: hidden;
}
.highlight-card::after {
  content: '';
  position: absolute;
  top: -50%; left: -50%; width: 200%; height: 200%;
  background: radial-gradient(circle, rgba(176, 141, 87, 0.1) 0%, rgba(255,255,255,0) 60%);
  pointer-events: none;
}
.highlight-title {
  font-weight: 700;
  color: #F2E8CF;
  font-size: 1.5rem;
}
.highlight-subtitle {
  color: #A8B5A8;
  font-size: 1.1rem;
}
.valor-total-display {
  font-size: 3.5rem;
  font-weight: 800;
  color: #B08D57;
  line-height: 1;
}
.valor-total-display .currency {
  font-size: 1.8rem;
  vertical-align: super;
  opacity: 0.8;
}

/* --- ACTION BUTTONS --- */
.action-buttons {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
}

.btn-icon {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  transition: all 0.2s;
  font-size: 1.25rem;
  cursor: pointer;
}

.btn-outline-main {
  background: transparent;
  color: #B08D57;
  border: 2px solid #B08D57;
  padding: 10px 20px;
  border-radius: 12px;
  font-weight: 700;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  transition: all 0.3s ease;
}

.btn-outline-main:hover {
  background: #B08D57;
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(176, 141, 87, 0.2);
}

.btn-icon.view { background: rgba(40, 75, 59, 0.08); color: #284B3B; border: none; }
.btn-icon.view:hover { background: #284B3B; color: white; transform: translateY(-2px); box-shadow: 0 4px 15px rgba(40, 75, 59, 0.2); }

.btn-icon.edit { background: rgba(176, 141, 87, 0.1); color: #B08D57; border: none; }
.btn-icon.edit:hover { background: #B08D57; color: white; transform: translateY(-2px); box-shadow: 0 4px 15px rgba(176, 141, 87, 0.2); }

.btn-icon.delete { background: rgba(220, 53, 69, 0.1); color: #dc3545; border: none; }
.btn-icon.delete:hover { background: #dc3545; color: white; transform: translateY(-2px); box-shadow: 0 4px 15px rgba(220, 53, 69, 0.2); }

@media (max-width: 991px) {
  .info-grid { grid-template-columns: 1fr; }
  .valor-total-display { font-size: 2.8rem; }
}
@media (max-width: 768px) {
  .hero-bg { height: 350px; }
  .main-content { margin-top: -60px; }
  .glass-card { padding: 25px; }
  .action-buttons { width: 100%; justify-content: flex-start; }
}

@media (max-width: 425px) {
  .hero-bg { height: 300px; padding-top: 100px; }
  .display-4 { font-size: 2rem; }
  .main-content { margin-top: -80px; }
  .glass-card { padding: 15px; }
  .info-grid { gap: 15px; }
  .info-item { padding: 15px; }
  .info-value { font-size: 1rem; }
  .valor-total-display { font-size: 2.2rem; }
  .valor-total-display .currency { font-size: 1.2rem; }
  .section-title { font-size: 1.5rem; }
}
</style>
