<template>
  <div class="p-6 max-w-7xl mx-auto">
    <h1 class="text-3xl font-extrabold mb-8 text-center text-gray-900">
      📋 Daftar Tugas Project: <span class="text-blue-600">{{ projectName }}</span>
    </h1>

    <!-- Tombol tambah tugas dan KanbanBoard -->
    <div
      v-if="permissions.includes('assign tasks') && userRole !== 'Admin'"
      class="mb-6 flex justify-end space-x-4"
    >
      <router-link
        :to="`/projects/${projectId}/tasks/create`"
        class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow-md hover:bg-blue-700 transition-colors duration-300 font-semibold"
      >
        + Tambah Tugas
      </router-link>

      <router-link
        :to="`/projects/${projectId}/kanban`"
        class="bg-indigo-600 text-white px-5 py-2 rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-300 font-semibold"
      >
        KanbanBoard
      </router-link>
    </div>

    <div v-if="loading" class="text-gray-500 text-center py-10 text-lg">
      Memuat data...
    </div>

    <div v-else-if="tasks.length > 0" class="overflow-x-auto rounded-lg shadow-lg border border-gray-200">
      <table class="w-full table-auto border-collapse">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="p-4 text-sm font-medium text-gray-700">Judul</th>
            <th class="p-4 text-sm font-medium text-gray-700">Deskripsi</th>
            <th class="p-4 text-sm font-medium text-gray-700">Status</th>
            <th class="p-4 text-sm font-medium text-gray-700">Dibuat Untuk</th>
            <th class="p-4 text-sm font-medium text-gray-700">Dibuat Oleh</th>
            <th class="p-4 text-sm font-medium text-gray-700">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="task in tasks"
            :key="task.id"
            class="odd:bg-white even:bg-gray-50 hover:bg-blue-50 transition-colors duration-200"
          >
            <td class="p-4 text-center text-gray-800 font-medium">{{ task.title }}</td>
            <td class="p-4 text-center text-gray-600 max-w-xs truncate">{{ task.description }}</td>
            <td class="p-4 text-center">
              <select
                :disabled="!permissions.includes('update tasks') || userRole === 'Admin'"
                v-model="task.status"
                @change="updateStatus(task)"
                class="border border-gray-300 rounded-md px-3 py-1 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none"
              >
                <option value="To Do">To Do</option>
                <option value="In Progress">In Progress</option>
                <option value="Done">Done</option>
              </select>
            </td>
            <td class="p-4 text-center text-gray-700">{{ task.assigned?.name || '-' }}</td>
            <td class="p-4 text-center text-gray-700">{{ getUserNameById(task.created_by) }}</td>
            <td class="p-4 text-center space-x-2">
              <router-link
                :to="`/tasks/${task.id}/comments`"
                class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm hover:bg-green-200 transition"
                title="Komentar"
              >
                💬
              </router-link>

              <router-link
                v-if="permissions.includes('update tasks') && userRole !== 'Admin'"
                :to="`/tasks/${task.id}/edit`"
                class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm hover:bg-blue-200 transition"
                title="Edit"
              >
                ✏️
              </router-link>

              <button
                v-if="permissions.includes('assign tasks') && userRole !== 'Admin'"
                @click="deleteTask(task.id)"
                class="inline-block bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm hover:bg-red-200 transition"
                title="Hapus"
              >
                🗑️
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-else class="text-gray-500 mt-8 text-center text-lg">
      Tidak ada tugas ditemukan.
    </div>
  </div>
</template>


<script setup>
import { ref, onMounted } from 'vue'
import axios from '@/axios'
import { useRoute } from 'vue-router'

const route = useRoute()
const projectId = route.params.id

const projectName = ref('')
const users = ref([])
const userRole = ref('')
const tasks = ref([])
const permissions = ref([])
const loading = ref(true)

const getUserNameById = (id) => {
  const user = users.value.find(u => u.id === id)
  return user ? user.name : '-'
}

const fetchUsers = async () => {
  try {
    const res = await axios.get('/users')
    users.value = res.data
  } catch (error) {
    console.error('Gagal mengambil data users:', error)
  }
}

const fetchProject = async () => {
  try {
    const res = await axios.get(`/projects/${projectId}`)
    projectName.value = res.data.name
  } catch (error) {
    console.error('Gagal mengambil data project:', error)
  }
}

const fetchTasks = async () => {
  try {
    const res = await axios.get(`/projects/${projectId}/tasks`)
    tasks.value = res.data
  } catch (error) {
    console.error('Gagal mengambil data tugas:', error)
  }
}

const fetchUserPermissions = async () => {
  try {
    const res = await axios.get('/auth/me')
    permissions.value = res.data.permissions ?? []
    userRole.value = res.data.role || ''
  } catch (error) {
    console.error('Gagal mengambil data user:', error)
  }
}

const updateStatus = async (task) => {
  try {
    await axios.put(`/tasks/${task.id}`, { status: task.status })
    alert('Status tugas berhasil diupdate')
  } catch (error) {
    console.error('Gagal update status tugas:', error)
    alert('Gagal update status tugas')
  }
}

const deleteTask = async (id) => {
  if (!confirm('Apakah kamu yakin ingin menghapus tugas ini?')) return

  try {
    await axios.delete(`/tasks/${id}`)
    tasks.value = tasks.value.filter(t => t.id !== id)
    alert('Tugas berhasil dihapus')
  } catch (error) {
    console.error('Gagal menghapus tugas:', error)
    alert('Gagal menghapus tugas')
  }
}

onMounted(async () => {
  loading.value = true
  await Promise.all([
    fetchUsers(),
    fetchProject(),
    fetchTasks(),
    fetchUserPermissions()
  ])
  loading.value = false
})
</script>

<style scoped>
table th,
table td {
  text-align: center;
  vertical-align: middle;
  transition: background-color 0.2s ease;
}
</style>