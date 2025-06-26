<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

// Estado reactivo
const envios = ref([])

// Cargar datos al montar el componente
onMounted(async () => {
  try {
    const response = await axios.get('http://localhost:8000/api/envios')
    envios.value = response.data
  } catch (error) {
    console.error('Error al cargar envíos:', error)
  }
})
</script>

<template>
  <div class="envio-view p-4">
    <h1 class="text-2xl font-bold mb-4">Lista de Envíos</h1>

    <table class="w-full border border-gray-300">
      <thead class="bg-gray-200">
        <tr>
          <th class="border px-2 py-1">ID</th>
          <th class="border px-2 py-1">Emisor</th>
          <th class="border px-2 py-1">Receptor</th>
          <th class="border px-2 py-1">Total</th>
          <th class="border px-2 py-1">Descripción</th>
          <th class="border px-2 py-1">Foto</th>
          <th class="border px-2 py-1">Ruta Unidad</th>
          <th class="border px-2 py-1">Horario</th>
          <th class="border px-2 py-1">Ruta</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="envio in envios" :key="envio.id">
          <td class="border px-2 py-1">{{ envio.id }}</td>
          <td class="border px-2 py-1">{{ envio.sender_name }}</td>
          <td class="border px-2 py-1">{{ envio.receiver_name }}</td>
          <td class="border px-2 py-1">${{ parseFloat(envio.total).toFixed(2) }}</td>
          <td class="border px-2 py-1">{{ envio.description }}</td>
          <td class="border px-2 py-1 text-center">
            <img v-if="envio.photo" :src="`/storage/${envio.photo}`" alt="Foto" width="80" />
            <span v-else>Sin foto</span>
          </td>
          <td class="border px-2 py-1">{{ envio.route_unit_id }}</td>
          <td class="border px-2 py-1">{{ envio.schedule_id }}</td>
          <td class="border px-2 py-1">{{ envio.route_id }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<style scoped>
table {
  border-collapse: collapse;
}
th, td {
  text-align: left;
  padding: 8px;
}
</style>
