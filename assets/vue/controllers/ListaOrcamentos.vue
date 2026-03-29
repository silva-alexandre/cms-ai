<template>
  <div class="orcamento-list-container py-5">
    <div class="container mt-4">
      <!-- Header Section -->
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 animate-on-scroll">
        <div>
          <span class="badge-premium mb-2">Painel de Controle</span>
          <h1 class="display-5 fw-800 text-crema mb-0">Orçamentos Registrados</h1>
          <p class="text-salvia opacity-75 mt-2">Acompanhe e gerencie todas as solicitações de clientes.</p>
        </div>
        <div class="mt-4 mt-md-0">
          <a :href="newPath" class="btn-main-gold">
            <i class="bi bi-plus-lg me-2"></i> Novo Orçamento
          </a>
        </div>
      </div>

      <!-- Filters & Search -->
      <div class="filters-panel glass-card mb-5 animate-on-scroll">
        <div class="row g-3 align-items-center">
          <div class="col-md-6 col-lg-8">
            <div class="search-wrap">
              <i class="bi bi-search search-icon"></i>
              <input 
                v-model="searchQuery" 
                type="text" 
                class="form-control custom-input" 
                placeholder="Buscar por título ou serviço..."
              >
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="d-flex gap-3 justify-content-md-end">
              <div class="dropdown w-100">
                <button class="btn btn-outline-salvia w-100 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                  Ordenar por: {{ sortLabel }}
                </button>
                <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
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

      <!-- Table Section -->
      <div class="table-container glass-card animate-on-scroll">
        <div v-if="filteredOrcamentos.length === 0" class="text-center py-5">
          <div class="empty-state-icon mb-3">
            <i class="bi bi-file-earmark-x display-1 opacity-25 text-salvia"></i>
          </div>
          <h3 class="text-crema">Nenhum registro encontrado</h3>
          <p class="text-salvia">Tente ajustar sua busca ou adicione um novo orçamento.</p>
        </div>

        <div v-else class="table-responsive">
          <table class="table custom-table">
            <thead>
              <tr>
                <th scope="col" class="ps-4">ID</th>
                <th scope="col">Título</th>
                <th scope="col">Serviço</th>
                <th scope="col">Área (m²)</th>
                <th scope="col">Valor Total</th>
                <th scope="col">Data</th>
                <th scope="col" class="text-end pe-4">Ações</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="orcamento in filteredOrcamentos" :key="orcamento.id" class="table-row-animate">
                <td class="ps-4 align-middle">
                  <span class="id-badge">#{{ orcamento.id }}</span>
                </td>
                <td class="align-middle">
                  <div class="fw-bold text-crema">{{ orcamento.titulo }}</div>
                </td>
                <td class="align-middle text-salvia">
                   {{ orcamento.id_servico?.nome || 'Não definido' }}
                </td>
                <td class="align-middle text-salvia">
                  {{ orcamento.qtd }}
                </td>
                <td class="align-middle text-bronze fw-bold">
                  {{ formatCurrency(orcamento.valorTotal) }}
                </td>
                <td class="align-middle text-salvia small">
                  {{ formatDate(orcamento.data) }}
                </td>
                <td class="pe-4 align-middle text-end">
                  <div class="d-flex gap-2 justify-content-end">
                    <a :href="getShowPath(orcamento.id)" class="action-btn view" title="Visualizar">
                      <i class="bi bi-eye"></i>
                    </a>
                    <a :href="getEditPath(orcamento.id)" class="action-btn edit" title="Editar">
                      <i class="bi bi-pencil-square"></i>
                    </a>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

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

    // Handle nested data for service name sorting if needed
    if (currentSort.value.key === 'servico') {
      valA = a.id_servico?.nome || ''
      valB = b.id_servico?.nome || ''
    }
    
    // Convert to numbers if comparing values
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

const getShowPath = (id) => {
  return decodeURIComponent(props.showPathBase).replace(':id', id)
}

const getEditPath = (id) => {
  return decodeURIComponent(props.editPathBase).replace(':id', id)
}

const formatCurrency = (value) => {
  if (value === null || value === undefined) return 'R$ 0,00'
  return new Intl.NumberFormat('pt-BR', {
    style: 'currency',
    currency: 'BRL'
  }).format(value)
}

const formatDate = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return new Intl.DateTimeFormat('pt-BR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(date)
}

onMounted(() => {
  // Add scroll animations logic if needed
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('fade-in-up')
      }
    })
  }, { threshold: 0.1 })

  document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el))
})
</script>

<style scoped>
.orcamento-list-container {
  min-height: 80vh;
  background-color: #1b3328;
  padding-top: 100px;
}

.text-crema { color: #F2E8CF; }
.text-salvia { color: #A8B5A8; }
.text-bronze { color: #B08D57; }
.fw-800 { font-weight: 800; }

.badge-premium {
  background: rgba(176, 141, 87, 0.15);
  color: #B08D57;
  padding: 8px 20px;
  border-radius: 50px;
  font-weight: 700;
  font-size: 0.75rem;
  letter-spacing: 1.5px;
  text-transform: uppercase;
  border: 1px solid rgba(176, 141, 87, 0.3);
  display: inline-block;
}

.glass-card {
  background: rgba(40, 75, 59, 0.4);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid rgba(176, 141, 87, 0.3);
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
}

.btn-main-gold {
  background: #B08D57;
  color: white !important;
  border: none;
  padding: 12px 28px;
  border-radius: 12px;
  font-weight: 700;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  transition: all 0.3s ease;
  box-shadow: 0 10px 20px rgba(176, 141, 87, 0.2);
}

.btn-main-gold:hover {
  background: #c19b62;
  transform: translateY(-2px);
  box-shadow: 0 15px 30px rgba(176, 141, 87, 0.3);
}

.search-wrap {
  position: relative;
}

.search-icon {
  position: absolute;
  left: 15px;
  top: 50%;
  transform: translateY(-50%);
  color: #B08D57;
  opacity: 0.7;
}

.custom-input {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(176, 141, 87, 0.2);
  color: #F2E8CF;
  padding: 12px 15px 12px 45px;
  border-radius: 10px;
}

.custom-input:focus {
  background: rgba(255, 255, 255, 0.08);
  border-color: #B08D57;
  box-shadow: 0 0 0 0.25rem rgba(176, 141, 87, 0.15);
  color: #F2E8CF;
}

.btn-outline-salvia {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(168, 181, 168, 0.3);
  color: #A8B5A8;
  padding: 12px 20px;
  border-radius: 10px;
  font-weight: 600;
}

.btn-outline-salvia:hover, .btn-outline-salvia:focus {
  background: rgba(168, 181, 168, 0.1);
  border-color: #A8B5A8;
  color: #F2E8CF;
}

.dropdown-menu-dark {
  background-color: #1b3328;
  border: 1px solid rgba(176, 141, 87, 0.3);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
}

.dropdown-item:hover {
  background-color: rgba(176, 141, 87, 0.2);
  color: #F2E8CF;
}

.custom-table {
  color: #F2E8CF;
  margin-bottom: 0;
}

.custom-table thead th {
  background: transparent;
  color: #B08D57;
  text-transform: uppercase;
  font-size: 0.7rem;
  letter-spacing: 1.5px;
  font-weight: 700;
  border-bottom: 1px solid rgba(176, 141, 87, 0.2);
  padding-bottom: 15px;
}

.custom-table tbody td {
  background: transparent;
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
  padding-top: 20px;
  padding-bottom: 20px;
}

.id-badge {
  background: rgba(176, 141, 87, 0.1);
  color: #B08D57;
  padding: 4px 10px;
  border-radius: 6px;
  font-size: 0.8rem;
  font-weight: 600;
}

.action-btn {
  width: 36px;
  height: 36px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 8px;
  transition: all 0.2s ease;
  text-decoration: none;
}

.action-btn.view {
  background: rgba(168, 181, 168, 0.1);
  color: #A8B5A8;
}

.action-btn.view:hover {
  background: rgba(168, 181, 168, 0.2);
  color: #F2E8CF;
}

.action-btn.edit {
  background: rgba(176, 141, 87, 0.1);
  color: #B08D57;
}

.action-btn.edit:hover {
  background: #B08D57;
  color: white;
}

/* Animations */
.animate-on-scroll {
  opacity: 0;
  transform: translateY(20px);
  transition: all 0.6s ease-out;
}

.fade-in-up {
  opacity: 1;
  transform: translateY(0);
}

.table-row-animate {
  transition: background-color 0.2s ease;
}

.table-row-animate:hover td {
  background-color: rgba(255, 255, 255, 0.02);
}

@media (max-width: 768px) {
  .glass-card {
    padding: 20px;
  }
  
  .custom-table thead {
    display: none;
  }
}
</style>
