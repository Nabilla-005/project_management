<template>
  <div class="p-6 max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-6">Tambah Tugas Baru untuk Project: {{ projectName }}</h1>

    <form @submit.prevent="submitForm" class="space-y-4">
      <div>
        <label for="title" class="block font-semibold mb-1">Judul Tugas</label>
        <input
          id="title"
          v-model="title"
          type="text"
          required
          class="w-full border rounded px-3 py-2"
          placeholder="Masukkan judul tugas"
        />
      </div>

      <div>
        <label for="description" class="block font-semibold mb-1">Deskripsi</label>
        <textarea
          id="description"
          v-model="description"
          rows="4"
          class="w-full border rounded px-3 py-2"
          placeholder="Masukkan deskripsi tugas"
        ></textarea>
      </div>

      <div>
        <label for="assigned" class="block font-semibold mb-1">Ditugaskan ke</label>
        <select
  id="assigned"
  v-model="assignedTo"
  class="w-full border rounded px-3 py-2"
>
  <option value="">-- Pilih User --</option>
  <option
    v-for="user in users"
    :key="user.id"
    :value="user.id"
  >
    {{ user.name }}
  </option>
</select>

      </div>

      <div>
        <label for="status" class="block font-semibold mb-1">Status</label>
        <select
          id="status"
          v-model="status"
          class="w-full border rounded px-3 py-2"
        >
          <option value="To Do">To Do</option>
          <option value="In Progress">In Progress</option>
          <option value="Done">Done</option>
        </select>
      </div>

      <div class="flex space-x-4 mt-6">
        <button
          type="submit"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
          :disabled="loading"
        >
          Simpan
        </button>
        <button
          type="button"
          class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400"
          @click="cancel"
          :disabled="loading"
        >
          Batal
        </button>
      </div>
    </form>

    <div v-if="errorMsg" class="mt-4 text-red-600 font-semibold">
      {{ errorMsg }}
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from '@/axios'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()
const projectId = route.params.id

const projectName = ref('')
const users = ref([])
const title = ref('')
const description = ref('')
const assignedTo = ref('')
const status = ref('To Do')
const errorMsg = ref('')
const loading = ref(false)

const fetchProject = async () => {
  try {
    const res = await axios.get(`/projects/${projectId}`)
    projectName.value = res.data.name
  } catch (error) {
    console.error('Gagal mengambil data project:', error)
  }
}

const fetchUsers = async () => {
  try {
    const res = await axios.get('/users')
    users.value = res.data
    console.log('Users fetched:', users.value)
  } catch (error) {
    console.error('Gagal mengambil data users:', error)
  }
}

const submitForm = async () => {
  errorMsg.value = ''
  if (!title.value.trim()) {
    errorMsg.value = 'Judul tugas harus diisi.'
    return
  }

  loading.value = true
  try {
    await axios.post(`/projects/${projectId}/tasks`, {
      title: title.value,
      description: description.value,
      assigned_to: assignedTo.value || null,
      status: status.value,
    })
    alert('Tugas berhasil ditambahkan')
    router.push(`/projects/${projectId}/tasks`)
  } catch (error) {
    console.error('Gagal menambahkan tugas:', error)
    errorMsg.value = 'Gagal menambahkan tugas. Silakan coba lagi.'
  } finally {
    loading.value = false
  }
}

const cancel = () => {
  router.push(`/projects/${projectId}/tasks`)
}

onMounted(() => {
  fetchProject()
  fetchUsers()
})
</script>

<style scoped>
/* Kalau mau tambahan styling, bisa di sini */
</style>