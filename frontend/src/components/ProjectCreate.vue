<template>
  <div class="max-w-2xl mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Tambah Project Baru</h2>

    <form @submit.prevent="createProject" class="space-y-4">
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
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          Simpan
        </button>
        <router-link to="/projects" class="text-gray-600 hover:underline">Batal</router-link>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from '@/axios'
import router from '@/router'

const form = ref({
  name: '',
  description: '',
  start_date: '',
  end_date: '',
})

const createProject = async () => {
  try {
    await axios.post('/projects', form.value)
    alert('Project berhasil ditambahkan')
    router.push('/projects')
  } catch (error) {
    console.error('Gagal menambahkan project:', error)
    alert('Gagal menambahkan project')
  }
}
</script>