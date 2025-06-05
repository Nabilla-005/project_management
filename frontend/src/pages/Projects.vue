<template>
  <div class="p-6">
    <h1 class="text-3xl font-extrabold mb-6 text-center text-gray-800">📋 Daftar Project</h1>

    <!-- Tombol tambah project -->
    <div class="flex justify-end mb-6" v-if="permissions.includes('create project')">
      <router-link
        to="/projects/create"
        class="inline-block bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition duration-200 font-semibold shadow-md"
      >
        + Tambah Project
      </router-link>
    </div>

    <div v-if="projects.length > 0" class="overflow-x-auto rounded-lg shadow-md">
      <table class="w-full table-auto border-collapse">
        <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
          <tr>
            <th class="p-4 border text-center">ID</th>
            <th class="p-4 border text-center">Nama</th>
            <th class="p-4 border text-center">Deskripsi</th>
            <th class="p-4 border text-center">Dibuat Oleh</th>
            <th class="p-4 border text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(project, index) in projects"
            :key="project.id"
            :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'"
            class="hover:bg-blue-50 transition"
          >
            <td class="p-4 border text-center font-medium text-gray-800">{{ project.id }}</td>
            <td class="p-4 border text-center text-gray-700">{{ project.name }}</td>
            <td class="p-4 border text-center text-gray-600">{{ project.description ?? '-' }}</td>
            <td class="p-4 border text-center text-gray-600">{{ project.creator?.name ?? '-' }}</td>
            <td class="p-4 border text-center">
              <div class="flex flex-wrap justify-center gap-2">
                <!-- Tugas -->
                <router-link
                  :to="`/projects/${project.id}/tasks`"
                  class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-1.5 rounded-md text-sm font-semibold transition duration-150 shadow-sm"
                >
                  Tugas
                </router-link>

                <!-- Edit -->
                <router-link
                  v-if="permissions.includes('update project')"
                  :to="`/projects/${project.id}/edit`"
                  class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-1.5 rounded-md text-sm font-semibold transition duration-150 shadow-sm"
                >
                  Edit
                </router-link>

                <!-- Hapus -->
                <button
                  v-if="permissions.includes('delete project')"
                  @click="deleteProject(project.id)"
                  class="bg-rose-500 hover:bg-rose-600 text-white px-4 py-1.5 rounded-md text-sm font-semibold transition duration-150 shadow-sm"
                >
                  Hapus
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-else class="text-gray-500 italic mt-6 text-center">Tidak ada project ditemukan 😢</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from '@/axios'
import router from '@/router'

const role = ref('')
const projects = ref([])
const permissions = ref([])

const fetchProjects = async () => {
  try {
    const response = await axios.get('/projects')
    projects.value = response.data
  } catch (error) {
    console.error('Gagal mengambil data project:', error)
  }
}

const fetchUserPermissions = async () => {
  try {
    const response = await axios.get('/auth/me')
    permissions.value = response.data.permissions ?? []
    role.value = response.data.role || ''
  } catch (error) {
    console.error('Gagal mengambil data user:', error)
  }
}

const deleteProject = async (id) => {
  if (!confirm('Apakah kamu yakin ingin menghapus project ini?')) return

  try {
    await axios.delete(`/projects/${id}`)
    projects.value = projects.value.filter((p) => p.id !== id)
    alert('Project berhasil dihapus')
  } catch (error) {
    console.error('Gagal menghapus project:', error)
    alert('Gagal menghapus project')
  }
}

onMounted(() => {
  fetchProjects()
  fetchUserPermissions()
})
</script>

<style scoped>
table th,
table td {
  text-align: left;
}
</style>