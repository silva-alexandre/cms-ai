<template>
  <section class="login-wrapper d-flex align-items-center justify-content-center">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-5 col-xl-4">
          <div class="glass-card-main animate-on-scroll p-4 p-md-5">
            <!-- Cabeçalho do Card -->
            <div class="text-center mb-4">
              <div class="login-icon-box mx-auto mb-3">
                <i class="bi bi-person-lock"></i>
              </div>
              <h2 class="display-6 fw-800 text-crema mb-2">Área Administrativa</h2>
              <div class="divider-gold mx-auto mb-4"></div>
              <p class="text-salvia small opacity-75">Entre com suas credenciais para gerenciar a plataforma.</p>
            </div>

            <!-- Formulário -->
            <form method="post" action="/login" class="login-form">
              <div v-if="error" class="alert alert-danger-custom mb-4 animate-shake">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ error }}
              </div>

              <div class="mb-4">
                <label for="email" class="form-label text-salvia small opacity-75">Endereço de E-mail</label>
                <div class="input-wrap">
                  <input
                    type="email"
                    id="email"
                    name="email"
                    :value="lastUsername"
                    class="custom-input-premium"
                    placeholder="name@company.com"
                    required
                    autofocus
                    autocomplete="email"
                  />
                  <div class="input-icon-mini">
                    <i class="bi bi-envelope"></i>
                  </div>
                </div>
              </div>

              <div class="mb-5">
                <div class="d-flex justify-content-between">
                  <label for="password" class="form-label text-salvia small opacity-75">Senha</label>
                  <a href="#" class="small text-bronze text-decoration-none opacity-75 hover-opacity-100">Esqueceu?</a>
                </div>
                <div class="input-wrap">
                  <input
                    type="password"
                    id="password"
                    name="password"
                    class="custom-input-premium"
                    placeholder="••••••••"
                    required
                    autocomplete="current-password"
                  />
                  <div class="input-icon-mini">
                    <i class="bi bi-shield-lock"></i>
                  </div>
                </div>
              </div>

              <input type="hidden" name="_csrf_token" :value="csrfToken" />

              <button type="submit" class="btn-main-gold w-100 py-3 mb-4">
                <span class="me-2">Acessar Painel</span>
                <i class="bi bi-arrow-right"></i>
              </button>

              <div class="text-center">
                <a href="/" class="text-salvia small text-decoration-none hover-text-crema">
                  <i class="bi bi-house-door me-1"></i> Voltar para o Site
                </a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { onMounted } from 'vue'

const props = defineProps({
  csrfToken: { type: String, required: true },
  lastUsername: { type: String, default: '' },
  error: { type: String, default: null }
})

onMounted(() => {
  // Animação simples de entrada
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
.login-wrapper {
  min-height: 100vh;
  background: #1b3328;
  background-image: 
    radial-gradient(circle at 10% 20%, rgba(40, 75, 59, 0.4) 0%, transparent 40%),
    radial-gradient(circle at 90% 80%, rgba(176, 140, 87, 0.1) 0%, transparent 40%),
    url('https://images.unsplash.com/photo-1589939705384-5185138a04b9?auto=format&fit=crop&w=1920&q=80');
  background-size: cover;
  background-position: center;
  position: relative;
}

.login-wrapper::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(27, 51, 40, 0.9) 0%, rgba(27, 51, 40, 0.7) 100%);
}

.glass-card-main {
  background: rgba(40, 75, 59, 0.35);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid rgba(176, 141, 87, 0.25);
  border-radius: 35px;
  box-shadow: 0 40px 100px rgba(0, 0, 0, 0.5);
  position: relative;
  z-index: 1;
}

.login-icon-box {
  width: 70px;
  height: 70px;
  background: rgba(176, 141, 87, 0.15);
  border: 1px solid rgba(176, 141, 87, 0.3);
  border-radius: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #B08D57;
  font-size: 2rem;
}

.fw-800 { font-weight: 800; }
.text-crema { color: #F2E8CF; }
.text-salvia { color: #A8B5A8; }
.text-bronze { color: #B08D57; }

.divider-gold {
  width: 60px;
  height: 3px;
  background: #B08D57;
  border-radius: 2px;
}

.custom-input-premium {
  background: rgba(40, 75, 59, 0.3) !important;
  border: 1px solid rgba(255, 255, 255, 0.1);
  color: #F2E8CF !important;
  padding: 14px 14px 14px 45px;
  border-radius: 14px;
  width: 100%;
  transition: all 0.3s ease;
}

.custom-input-premium:focus {
  outline: none;
  border-color: #B08D57;
  background: rgba(40, 75, 59, 0.5) !important;
  box-shadow: 0 0 15px rgba(176, 141, 87, 0.15);
}

.input-wrap {
  position: relative;
}

.input-icon-mini {
  position: absolute;
  left: 18px;
  top: 50%;
  transform: translateY(-50%);
  color: #B08D57;
  opacity: 0.7;
}

.btn-main-gold {
  background: #B08D57;
  color: white;
  border: none;
  border-radius: 14px;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  box-shadow: 0 10px 20px rgba(176, 141, 87, 0.2);
}

.btn-main-gold:hover {
  background: #c19b62;
  transform: translateY(-2px);
  box-shadow: 0 15px 30px rgba(176, 141, 87, 0.3);
}

.alert-danger-custom {
  background: rgba(220, 53, 69, 0.15);
  border: 1px solid rgba(220, 53, 69, 0.3);
  color: #ffa5a5;
  padding: 12px 15px;
  border-radius: 12px;
  font-size: 0.9rem;
}

.hover-text-crema:hover { color: #F2E8CF; }
.hover-opacity-100:hover { opacity: 1 !important; }

/* Animações */
.animate-on-scroll {
  opacity: 0;
  transform: translateY(20px);
  transition: all 0.8s cubic-bezier(0.165, 0.84, 0.44, 1);
}

.fade-in-up {
  opacity: 1;
  transform: translateY(0);
}

@keyframes shake {
  0%, 100% { transform: translateX(0); }
  25% { transform: translateX(-5px); }
  75% { transform: translateX(5px); }
}

.animate-shake {
  animation: shake 0.4s ease-in-out;
}

/* Responsividade Mobile Extrema */
@media (max-width: 767px) {
  .login-wrapper {
    background-attachment: scroll;
    padding-top: 100px;
    padding-bottom: 20px;
  }
  
  .glass-card-main {
    padding: 30px 20px !important;
    border-radius: 25px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.3);
  }

  .display-6 {
    font-size: 1.5rem;
  }

  .custom-input-premium {
    padding: 12px 12px 12px 40px;
    font-size: 0.95rem;
  }

  .btn-main-gold {
    padding: 14px 20px !important;
  }
}

/* Prevenir zoom no iOS ao focar inputs */
@media screen and (max-width: 767px) {
  input, select, textarea {
    font-size: 16px !important;
  }
}
</style>