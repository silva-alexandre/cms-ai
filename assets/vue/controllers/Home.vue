<template>
  <section class="hero">
    <h1>{{ title }}</h1>
    <p>{{ subtitle }}</p>
    <button @click="clickAction">Clique</button>
  </section>
</template>

<script setup>
import { ref } from 'vue'

const title = ref('CMS Dev')
const subtitle = ref('Symfony 8 + UX Vue funcionando 🚀')

const clickAction = () => {
  alert('Vue integrado via UX com sucesso!')
}
</script>

<style scoped>
.hero {
  padding: 100px;
  background: linear-gradient(135deg, #111, #333);
  color: white;
  text-align: center;
}
button {
  margin-top: 20px;
  padding: 10px 20px;
  background: #42b883;
  border: none;
  color: white;
}
</style>