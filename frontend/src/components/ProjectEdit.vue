<template>
  <div class="max-w-2xl mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Edit Project</h2>

    <form @submit.prevent="updateProject" class="space-y-4">
      <div>
        <label class="block mb-1 font-medium">Nama Project</label>
        <input v-model="form.name" type="text" class="w-full border p-2 rounded" required />
      </div>

      <div>
        <label class="block mb-1 font-medium">Deskripsi</label>
        <textarea v-model="form.description" class="w-full border p-2 rounded" />
      </div>

      <div>
        <label class="block mb-1 font-medium">Tanggal Mulai</label>
        <input v-model="form.start_date" type="date" class="w-full border p-2 rounded" required />
      </div>

      <div>
        <label class="block mb-1 font-medium">Tanggal Selesai</label>
        <input v-model="form.end_date" type="date" class="w-full border p-2 rounded" required />
      </div>

      <div class="flex items-center gap-4">
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
          Update
        </button>
        <router-link to="/projects" class="text-gray-600 hover:underline">Batal</router-link>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from '@/axios'
import router from '@/router'
import { useRoute } from 'vue-router'

const route = useRoute()
const id = route.params.id

const form = ref({
  name: '',
  description: '',
  start_date: '',
  end_date: '',
})

const fetchProject = async () => {
  try {
    const response = await axios.get(`/projects/${id}`)
    form.value = {
      name: response.data.name,
      description: response.data.description,
      start_date: response.data.start_date,
      end_date: response.data.end_date,
    }
  } catch (error) {
    console.error('Gagal memuat data project:', error)
    alert('Gagal memuat project')
    router.push('/projects')
  }
}

const updateProject = async () => {
  try {
    await axios.put(`/projects/${id}`, form.value)
    alert('Project berhasil diperbarui')
    router.push('/projects')
  } catch (error) {
    console.error('Gagal memperbarui project:', error)
    alert('Gagal memperbarui project')
  }
}

onMounted(fetchProject)
</script>