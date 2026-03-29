<template>
  <div class="servico-details-container">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
      <div>
        <h2 class="section-title text-verde mb-1">
          <i class="bi bi-tag me-2"></i>Detalhes do Serviço
        </h2>
        <p class="text-muted mb-0">Informações completas sobre o serviço selecionado.</p>
      </div>
      <div class="d-flex align-items-center gap-3 flex-wrap">
        <a href="/servico/" class="btn-outline-main">
          <i class="bi bi-arrow-left me-2"></i> Voltar para Lista
        </a>
        <div class="action-buttons">
        <a :href="`/servico/${servico.id}/edit`" class="btn-icon edit" title="Editar Serviço">
          <i class="bi bi-pencil"></i>
        </a>
        <form method="post" :action="deleteUrl" @submit="confirmDelete" style="margin: 0;">
          <input type="hidden" name="_token" :value="csrfToken">
          <button type="submit" class="btn-icon delete" title="Excluir Serviço">
            <i class="bi bi-trash"></i>
          </button>
        </form>
      </div>
    </div>
    </div>

    <div class="row g-4">
      <div class="col-lg-8">
        <div class="glass-card mb-4">
          <h3 class="card-title"><i class="bi bi-info-circle me-2"></i>Informações Gerais</h3>
          <div class="info-grid mt-4">
            <div class="info-item">
              <label>ID do Serviço</label>
              <div class="info-value">#{{ servico.id }}</div>
            </div>
            <div class="info-item">
              <label>Nome</label>
              <div class="info-value text-verde fw-bold">{{ servico.nome }}</div>
            </div>
            <div class="info-item full-width">
              <label>Detalhes</label>
              <div class="info-value">{{ servico.detalhe }}</div>
            </div>
            <div class="info-item full-width">
              <label>Preço</label>
              <div class="info-value">
                <span class="price-pill" v-if="servico.preco && servico.preco !== '0'">
                  R$ {{ servico.preco }}
                </span>
                <span class="text-muted" v-else>Sob consulta</span>
              </div>
            </div>
          </div>
        </div>

      </div>

      <div class="col-lg-4">
        <div class="glass-card h-100">
          <h3 class="card-title mb-4"><i class="bi bi-images me-2"></i>Galeria de Fotos</h3>
          
          <div v-if="servico.foto && servico.foto.length > 0" class="gallery-grid">
            <div v-for="(f, idx) in servico.foto" :key="idx" class="gallery-item">
              <img :src="`/uploads/${f}`" :alt="`Foto ${idx + 1}`" class="img-fluid rounded" />
            </div>
          </div>
          <div v-else class="empty-state text-center py-5 text-muted">
            <i class="bi bi-image fs-1 mb-3 d-block opacity-50"></i>
            <p class="mb-0">Nenhuma imagem cadastrada<br><small>Adicione fotos editando o serviço.</small></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  servico: {
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

const confirmDelete = (e) => {
  if (!confirm('Tem certeza de que deseja excluir este serviço? Esta ação é irreversível.')) {
    e.preventDefault();
  }
}
</script>

<style scoped>
.servico-details-container {
  padding: 130px 0 30px;
  width: 100%;
}

.text-verde { color: #284B3B; }
.section-title { font-weight: 800; font-size: 2rem; }

.btn-main {
  background: #B08D57;
  color: white;
  border: none;
  padding: 12px 24px;
  border-radius: 12px;
  font-weight: 700;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(176, 141, 87, 0.3);
}

.btn-main:hover {
  background: #c19b62;
  transform: translateY(-2px);
  color: white;
  box-shadow: 0 8px 20px rgba(176, 141, 87, 0.4);
}

.action-buttons {
  display: flex;
  justify-content: flex-end;
  gap: 8px;
}

.btn-icon {
  width: 45px;
  height: 45px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  transition: all 0.2s;
  font-size: 1.2rem;
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

.btn-icon.view {
  background: rgba(40, 75, 59, 0.08);
  color: #284B3B;
}
.btn-icon.view:hover {
  background: #284B3B;
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(40, 75, 59, 0.2);
}

.btn-icon.edit {
  background: rgba(176, 141, 87, 0.1);
  color: #B08D57;
}
.btn-icon.edit:hover {
  background: #B08D57;
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(176, 141, 87, 0.2);
}

.btn-icon.delete {
  background: rgba(220, 53, 69, 0.1);
  color: #dc3545;
  border: none;
  cursor: pointer;
}
.btn-icon.delete:hover {
  background: #dc3545;
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(220, 53, 69, 0.2);
}

.glass-card {
  background: #ffffff;
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
  border: 1px solid rgba(176, 141, 87, 0.15);
}

.card-title {
  color: #8E8577;
  font-size: 1.1rem;
  font-weight: 800;
  margin: 0;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  border-bottom: 2px solid rgba(176, 141, 87, 0.1);
  padding-bottom: 15px;
}

.border-danger {
  border-bottom-color: rgba(220, 53, 69, 0.2) !important;
}

.info-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}

.info-item {
  background: #fdfaf3;
  padding: 20px;
  border-radius: 15px;
  border: 1px solid rgba(176, 141, 87, 0.1);
  transition: all 0.3s ease;
}

.info-item:hover {
  background: white;
  box-shadow: 0 5px 15px rgba(0,0,0,0.03);
  transform: translateY(-2px);
}

.info-item.full-width {
  grid-column: 1 / -1;
}

.info-item label {
  display: block;
  font-size: 0.8rem;
  color: #8E8577;
  font-weight: 800;
  margin-bottom: 8px;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.info-value {
  font-size: 1.15rem;
  color: #333;
  line-height: 1.5;
}

.price-pill {
  background: rgba(176, 141, 87, 0.1);
  color: #B08D57;
  padding: 8px 18px;
  border-radius: 30px;
  font-weight: 800;
  display: inline-block;
}

.danger-zone {
  border: 1px solid rgba(220, 53, 69, 0.2);
  background: linear-gradient(to bottom, #ffffff, #fffcfc);
}

.gallery-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
  margin-top: 20px;
}

.gallery-item img {
  width: 100%;
  aspect-ratio: 1;
  object-fit: cover;
  border-radius: 15px;
  border: 2px solid transparent;
  transition: all 0.3s ease;
  box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}

.gallery-item img:hover {
  transform: scale(1.05);
  border-color: #B08D57;
  box-shadow: 0 8px 25px rgba(0,0,0,0.15);
  z-index: 10;
  position: relative;
}

.empty-state {
  background: #f8f9fa;
  border-radius: 15px;
  border: 2px dashed rgba(176, 141, 87, 0.2);
  margin-top: 20px;
}

@media (max-width: 991px) {
  .info-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  .servico-details-container {
    padding: 100px 0 20px;
  }
  .glass-card {
    padding: 20px;
  }
  .section-title {
    font-size: 1.5rem;
  }
  .btn-main, .btn-outline-main {
    padding: 10px 20px;
    font-size: 0.95rem;
  }
  .action-buttons {
    width: 100%;
    justify-content: flex-start;
  }
}

@media (max-width: 425px) {
  .servico-details-container {
    padding-top: 90px;
  }
  .section-title {
    font-size: 1.3rem;
  }
  .glass-card {
    padding: 15px;
  }
  .info-item {
    padding: 15px;
  }
  .info-value {
    font-size: 1rem;
  }
  .price-pill {
    font-size: 0.9rem;
    padding: 6px 12px;
  }
  .btn-icon {
    width: 35px;
    height: 35px;
    font-size: 1rem;
  }
}
</style>
