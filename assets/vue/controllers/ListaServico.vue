<template>
  <div class="servicos-container">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
      <div>
        <h2 class="section-title text-verde mb-1"><i class="bi bi-tools me-2"></i>Gerenciar Serviços</h2>
        <p class="text-muted mb-0">Gerencie o portfólio de serviços oferecidos pelo sistema.</p>
      </div>
      <a href="/servico/new" class="btn-main">
        <i class="bi bi-plus-lg me-2"></i>  Novo Serviço
      </a>
    </div>

    <div class="glass-card table-responsive">
      <table class="table custom-table align-middle mb-0">
        <thead>
          <tr>
            <th width="80">ID</th>
            <th>Fotos</th>
            <th>Nome do Serviço</th>
            <th>Detalhe</th>
            <th>Preço (m²)</th>
            <th class="text-end">Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="servico in servicosData" :key="servico.id" class="table-row">
            <td><span class="badge-id">#{{ servico.id }}</span></td>
            <td>
              <div class="photos-container">
                <template v-if="servico.foto && servico.foto.length > 0">
                  <div class="photo-thumbnail" 
                       v-for="(f, idx) in servico.foto.slice(0, 3)" 
                       :key="idx"
                       :style="{ backgroundImage: `url('/uploads/${f}')`, zIndex: 3 - idx }">
                  </div>
                  <div class="photo-more" v-if="servico.foto.length > 3" :style="{ zIndex: 0 }">
                    +{{ servico.foto.length - 3 }}
                  </div>
                </template>
                <div v-else class="photo-placeholder">
                  <i class="bi bi-image text-muted"></i>
                </div>
              </div>
            </td>
            <td><strong class="text-verde">{{ servico.nome }}</strong></td>
            <td class="text-muted"><small>{{ servico.detalhe }}</small></td>
            <td>
              <span class="price-pill" v-if="servico.preco && servico.preco !== '0'">
                R$ {{ servico.preco }}
              </span>
              <span class="text-muted" v-else>Sob consulta</span>
            </td>
            <td class="text-end">
              <div class="action-buttons">
                <a :href="`/servico/${servico.id}`" class="btn-icon view" title="Visualizar">
                  <i class="bi bi-eye"></i>
                </a>
                <a :href="`/servico/${servico.id}/edit`" class="btn-icon edit" title="Editar">
                  <i class="bi bi-pencil"></i>
                </a>
              </div>
            </td>
          </tr>
          
          <tr v-if="servicosData.length === 0 && !loading">
            <td colspan="6" class="text-center py-5 text-muted">
              <i class="bi bi-inbox fs-1 mb-3 d-block opacity-50"></i>
              Nenhum serviço cadastrado ainda.
            </td>
          </tr>
          
          <tr v-if="loading">
            <td colspan="6" class="text-center py-5">
              <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Carregando...</span>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const props = defineProps({
  initialServicos: {
    type: Array,
    default: () => []
  }
})

const servicosData = ref(props.initialServicos)
const loading = ref(false)

onMounted(async () => {
  if (servicosData.value.length === 0) {
    loading.value = true
    try {
      const response = await fetch('/servico/api')
      if (response.ok) {
        servicosData.value = await response.json()
      }
    } catch (e) {
      console.error('Erro ao buscar serviços:', e)
    } finally {
      loading.value = false
    }
  }
})
</script>

<style scoped>
.servicos-container {
  padding: 130px 0 30px;
  width: 100%;
}

.text-verde { color: #284B3B; }
.section-title { font-weight: 800; font-size: 2rem; }

.btn-main {
  background: #B08D57;
  color: white;
  border: none;
  padding: 14px 28px;
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

.glass-card {
  background: #ffffff;
  border-radius: 20px;
  padding: 10px 20px 20px 20px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
  border: 1px solid rgba(176, 141, 87, 0.15);
  overflow: hidden;
}

.custom-table th {
  background: #ffffff;
  color: #8E8577;
  font-weight: 700;
  border-bottom: 2px solid rgba(176, 141, 87, 0.15);
  padding: 18px 15px;
  text-transform: uppercase;
  font-size: 0.8rem;
  letter-spacing: 0.5px;
}

.custom-table td {
  padding: 18px 15px;
  border-bottom: 1px solid rgba(0, 0, 0, 0.03);
  vertical-align: middle;
}

.table-row {
  transition: all 0.3s ease;
}

.table-row:hover td {
  background: rgba(176, 141, 87, 0.02);
}

.table-row:last-child td {
  border-bottom: none;
}

.badge-id {
  background: #fdfaf3;
  color: #8E8577;
  padding: 6px 12px;
  border-radius: 8px;
  font-weight: 700;
  font-size: 0.85rem;
  border: 1px solid rgba(176, 141, 87, 0.2);
}

.photos-container {
  display: flex;
  align-items: center;
  padding-left: 10px;
}

.photo-thumbnail {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  background-size: cover;
  background-position: center;
  border: 3px solid #fff;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  margin-left: -15px;
  position: relative;
  transition: transform 0.3s ease;
}

.photo-thumbnail:hover {
  transform: translateY(-5px) scale(1.1);
  z-index: 10 !important;
}

.photo-more {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  background: #284B3B;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.8rem;
  font-weight: 800;
  border: 3px solid #fff;
  margin-left: -15px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.photo-placeholder {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  background: #f8f9fa;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px dashed #dee2e6;
  font-size: 1.2rem;
}

.price-pill {
  background: rgba(176, 141, 87, 0.1);
  color: #B08D57;
  padding: 8px 16px;
  border-radius: 20px;
  font-weight: 800;
  font-size: 0.95rem;
}

.action-buttons {
  display: flex;
  justify-content: flex-end;
  gap: 8px;
}

.btn-icon {
  width: 40px;
  height: 40px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  transition: all 0.2s;
  font-size: 1.1rem;
}

.btn-icon.view {
  background: rgba(40, 75, 59, 0.08);
  color: #284B3B;
}
.btn-icon.view:hover {
  background: #284B3B;
  color: white;
  transform: translateY(-2px);
}

.btn-icon.edit {
  background: rgba(176, 141, 87, 0.1);
  color: #B08D57;
}
.btn-icon.edit:hover {
  background: #B08D57;
  color: white;
  transform: translateY(-2px);
}

@media (max-width: 768px) {
  .glass-card { 
    padding: 10px; 
    border-radius: 15px; 
  }
  .section-title { font-size: 1.5rem; }
  .btn-main { width: 100%; justify-content: center; }
  
  .custom-table th, .custom-table td { 
    padding: 12px 10px; 
    white-space: nowrap;
  }
}
</style>
