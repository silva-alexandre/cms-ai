<template>
  <div class="orcamentos-container">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
      <div>
        <h2 class="section-title text-verde mb-1"><i class="bi bi-calculator me-2"></i>Gerenciar Orçamentos</h2>
        <p class="text-muted mb-0">Acompanhe e gerencie todas as solicitações de clientes.</p>
      </div>
      <a :href="newPath" class="btn-main">
        <i class="bi bi-plus-lg me-2"></i> Novo Orçamento
      </a>
    </div>

    <!-- Filters & Search -->
    <div class="filters-panel glass-card mb-4">
      <div class="row g-3 align-items-center">
        <div class="col-md-6 col-lg-8">
          <div class="search-wrap position-relative">
            <i class="bi bi-search search-icon position-absolute" style="left: 15px; top: 12px; color: #8E8577;"></i>
            <input 
              v-model="searchQuery" 
              type="text" 
              class="form-control custom-input" 
              style="padding-left: 45px; border-radius: 12px; border: 1px solid rgba(176, 141, 87, 0.2);"
              placeholder="Buscar por título ou id..."
            >
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="d-flex gap-3 justify-content-md-end">
            <div class="dropdown w-100">
              <button class="btn w-100 dropdown-toggle" type="button" data-bs-toggle="dropdown" style="border-radius: 12px; border: 1px solid rgba(142, 133, 119, 0.3); padding: 8px 15px; background: transparent; color: #8E8577; font-weight: 600;">
                Ordenar por: {{ sortLabel }}
              </button>
              <ul class="dropdown-menu dropdown-menu-end" style="border: 1px solid rgba(176, 141, 87, 0.2); border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
                <li><a class="dropdown-item" href="#" @click.prevent="sortBy('data', 'desc')">Mais Recentes</a></li>
                <li><a class="dropdown-item" href="#" @click.prevent="sortBy('data', 'asc')">Mais Antigos</a></li>
                <li><a class="dropdown-item" href="#" @click.prevent="sortBy('valorTotal', 'desc')">Maior Valor</a></li>
                <li><a class="dropdown-item" href="#" @click.prevent="sortBy('titulo', 'asc')">Título (A-Z)</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="glass-card table-responsive">
      <table class="table custom-table align-middle mb-0">
        <thead>
          <tr>
            <th width="80">ID</th>
            <th>Título</th>
            <th>Serviço</th>
            <th>Área (m²)</th>
            <th>Valor Total</th>
            <th>Data</th>
            <th class="text-end">Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="orcamento in filteredOrcamentos" :key="orcamento.id" class="table-row">
            <td><span class="badge-id">#{{ orcamento.id }}</span></td>
            <td><strong class="text-verde">{{ orcamento.titulo }}</strong></td>
            <td class="text-muted"><small>{{ orcamento.id_servico?.nome || 'Não definido' }}</small></td>
            <td><span class="text-muted">{{ orcamento.qtd }}</span></td>
            <td>
              <span class="price-pill">
                {{ formatCurrency(orcamento.valorTotal) }}
              </span>
            </td>
            <td class="text-muted"><small>{{ formatDate(orcamento.data) }}</small></td>
            <td class="text-end">
              <div class="action-buttons">
                <a :href="getShowPath(orcamento.id)" class="btn-icon view" title="Visualizar">
                  <i class="bi bi-eye"></i>
                </a>
                <a :href="getEditPath(orcamento.id)" class="btn-icon edit" title="Editar">
                  <i class="bi bi-pencil"></i>
                </a>
              </div>
            </td>
          </tr>
          
          <tr v-if="filteredOrcamentos.length === 0">
            <td colspan="7" class="text-center py-5 text-muted">
              <i class="bi bi-inbox fs-1 mb-3 d-block opacity-50"></i>
              Nenhum registro encontrado.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  orcamentos: {
    type: Array,
    required: true,
    default: () => []
  },
  newPath: {
    type: String,
    default: '/orcamento/new'
  },
  showPathBase: {
    type: String,
    default: '/orcamento/'
  },
  editPathBase: {
    type: String,
    default: '/orcamento/:id/edit'
  }
})

// === Estado Reativo ===
const searchQuery = ref('')
const currentSort = ref({ key: 'data', order: 'desc' })

// === Computed ===
const sortLabel = computed(() => {
  const labels = {
    'data-desc': 'Mais Recentes',
    'data-asc': 'Mais Antigos',
    'valorTotal-desc': 'Maior Valor',
    'titulo-asc': 'Título (A-Z)'
  }
  return labels[`${currentSort.value.key}-${currentSort.value.order}`] || 'Mais Recentes'
})

const filteredOrcamentos = computed(() => {
  let result = [...props.orcamentos]

  // Filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    result = result.filter(o => 
      o.titulo?.toLowerCase().includes(query) || 
      o.id_servico?.nome?.toLowerCase().includes(query) ||
      o.id.toString().includes(query)
    )
  }

  // Sort
  result.sort((a, b) => {
    let modifier = currentSort.value.order === 'asc' ? 1 : -1
    let valA = a[currentSort.value.key]
    let valB = b[currentSort.value.key]

    if (currentSort.value.key === 'servico') {
      valA = a.id_servico?.nome || ''
      valB = b.id_servico?.nome || ''
    }
    
    if (currentSort.value.key === 'valorTotal' || currentSort.value.key === 'qtd') {
      valA = parseFloat(valA) || 0
      valB = parseFloat(valB) || 0
    }

    if (valA < valB) return -1 * modifier
    if (valA > valB) return 1 * modifier
    return 0
  })

  return result
})

// === Métodos ===
const sortBy = (key, order) => {
  currentSort.value = { key, order }
}

const getShowPath = (id) => decodeURIComponent(props.showPathBase).replace(':id', id)
const getEditPath = (id) => decodeURIComponent(props.editPathBase).replace(':id', id)

const formatCurrency = (value) => {
  if (value === null || value === undefined) return 'R$ 0,00'
  return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value)
}

const formatDate = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return new Intl.DateTimeFormat('pt-BR', {
    day: '2-digit', month: '2-digit', year: 'numeric',
    hour: '2-digit', minute: '2-digit'
  }).format(date)
}
</script>

<style scoped>
.orcamentos-container {
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
  padding: 20px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
  border: 1px solid rgba(176, 141, 87, 0.15);
  overflow: hidden;
}

.custom-input {
  box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
}
.custom-input:focus {
  outline: none;
  box-shadow: inset 0 2px 4px rgba(0,0,0,0.02), 0 0 0 3px rgba(176, 141, 87, 0.2);
  border-color: #B08D57 !important;
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

.price-pill {
  background: rgba(176, 141, 87, 0.1);
  color: #B08D57;
  padding: 8px 16px;
  border-radius: 20px;
  font-weight: 800;
  font-size: 0.95rem;
  display: inline-block;
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
    padding: 15px; 
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
