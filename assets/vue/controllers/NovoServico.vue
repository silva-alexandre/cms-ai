<template>
  <div class="glass-card form-container p-4">
    <div class="row g-4">
      <div class="col-md-6">
        <label class="form-label text-verde fw-bold">Nome do Serviço</label>
        <div class="input-group-custom">
          <i class="bi bi-tag input-icon"></i>
          <input type="text" class="form-control-custom" :name="fieldNames.nome" v-model="formData.nome" placeholder="Ex: Pintura Premium" required>
        </div>
      </div>

      <div class="col-md-6">
        <label class="form-label text-verde fw-bold">Preço (m²)</label>
        <div class="input-group-custom">
          <span class="input-prefix">R$</span>
          <input type="text" class="form-control-custom ps-2" :name="fieldNames.preco" v-model="formData.preco" placeholder="Ex: 45.00">
        </div>
      </div>

      <div class="col-12">
        <label class="form-label text-verde fw-bold">Detalhe/Descrição</label>
        <div class="input-group-custom align-items-start">
          <i class="bi bi-text-paragraph input-icon mt-3"></i>
          <textarea class="form-control-custom" :name="fieldNames.detalhe" v-model="formData.detalhe" rows="3" placeholder="Insira uma breve descrição do serviço oferecido..."></textarea>
        </div>
      </div>

      <div class="col-12">
        <label class="form-label text-verde fw-bold">Fotos do Serviço</label>
        
        <!-- Fotos Atuais Preview -->
        <div v-if="formData.fotosExistentes && formData.fotosExistentes.length > 0" class="mb-3">
          <p class="small text-muted mb-2"><i class="bi bi-images me-1"></i> Fotos atuais (novas fotos serão adicionadas à lista):</p>
          <div class="d-flex flex-wrap gap-2">
            <div class="existing-photo" v-for="(foto, i) in formData.fotosExistentes" :key="i">
              <img :src="`/uploads/${foto}`" alt="Preview da foto" loading="lazy">
            </div>
          </div>
        </div>
        
        <div class="file-upload-wrapper mt-2 mb-3">
          <label class="file-upload-label m-0 cursor-pointer w-100">
            <div class="file-upload-content text-center">
              <i class="bi bi-cloud-arrow-up display-4 text-muted mb-3 d-block"></i>
              <h5 class="fw-bold mb-1 text-verde">Clique para selecionar novas fotos</h5>
              <p class="text-muted small mb-0">Arquivos suportados: JPG, PNG, WEBP. Permite múltiplos arquivos.</p>
              
              <div class="selected-files mt-3 d-flex flex-wrap justify-content-center gap-2" v-if="selectedFileNames.length > 0">
                <span class="badge bg-sucesso" v-for="name in selectedFileNames" :key="name">
                  <i class="bi bi-image me-1"></i> {{ name }}
                </span>
              </div>
            </div>
            <!-- fieldNames.foto must include [] in the end to support multiple files -->
            <input type="file" :name="fotoFieldName" multiple @change="handleFileChange" class="d-none" accept="image/*">
          </label>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, computed } from 'vue'

const props = defineProps({
  fieldNames: { type: Object, default: () => ({}) },
  initialData: { type: Object, default: () => ({}) }
})

const formData = reactive({
  nome: props.initialData.nome || '',
  detalhe: props.initialData.detalhe || '',
  preco: props.initialData.preco || '',
  fotosExistentes: props.initialData.fotosExistentes || []
})

const selectedFileNames = ref([])

const fotoFieldName = computed(() => {
  const name = props.fieldNames.foto || 'servico[foto]'
  return name.endsWith('[]') ? name : name + '[]'
})

const handleFileChange = (e) => {
  const files = e.target.files
  selectedFileNames.value = []
  for (let i = 0; i < files.length; i++) {
    selectedFileNames.value.push(files[i].name)
  }
}
</script>

<style scoped>
.glass-card {
  background: #ffffff;
  border-radius: 20px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
  border: 1px solid rgba(176, 141, 87, 0.15);
  margin-top: 130px;
}

.text-verde { color: #284B3B; }
.cursor-pointer { cursor: pointer; }

.input-group-custom {
  display: flex;
  align-items: center;
  background: #fdfaf3;
  border: 1px solid rgba(176, 141, 87, 0.3);
  border-radius: 12px;
  padding: 0 15px;
  transition: all 0.3s ease;
}

.input-group-custom:focus-within {
  border-color: #B08D57;
  box-shadow: 0 0 0 3px rgba(176, 141, 87, 0.15);
  background: #ffffff;
}

.input-icon {
  color: #B08D57;
  font-size: 1.2rem;
  margin-right: 10px;
}

.input-prefix {
  color: #B08D57;
  font-weight: 700;
  margin-right: 5px;
}

.form-control-custom {
  border: none;
  background: transparent;
  width: 100%;
  padding: 15px 0;
  font-size: 1rem;
  color: #284B3B;
  outline: none;
}

.form-control-custom::placeholder {
  color: #A8B5A8;
  opacity: 0.7;
}

.file-upload-wrapper {
  background: #fdfaf3;
  border: 2px dashed rgba(176, 141, 87, 0.4);
  border-radius: 16px;
  padding: 40px 20px;
  transition: all 0.3s ease;
}

.file-upload-wrapper:hover {
  background: #fff;
  border-color: #B08D57;
}

.existing-photo {
  width: 80px;
  height: 80px;
  border-radius: 10px;
  overflow: hidden;
  border: 2px solid #fff;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  background: #f8f9fa;
  display: flex;
  justify-content: center;
  align-items: center;
}

.existing-photo img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.bg-sucesso {
  background: rgba(40, 75, 59, 0.1);
  color: #284B3B;
  border: 1px solid rgba(40, 75, 59, 0.2);
  padding: 6px 12px;
  border-radius: 20px;
  font-weight: 600;
}
</style>
