<template>
  <div class="container">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-semibold text-gray-800">Manajemen Pengguna</h1>
    </div>

    <div class="overflow-x-auto rounded-lg shadow">
      <table class="min-w-full bg-white border border-gray-200">
        <thead>
          <tr class="bg-gray-100 text-gray-700 text-sm">
            <th class="text-left py-3 px-4 border-b">Nama</th>
            <th class="text-left py-3 px-4 border-b">Email</th>
            <th class="text-left py-3 px-4 border-b">Role</th>
            <th class="text-left py-3 px-4 border-b">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="user in users"
            :key="user.id"
            class="hover:bg-gray-50 transition"
          >
            <td class="py-3 px-4 border-b text-gray-800">{{ user.name }}</td>
            <td class="py-3 px-4 border-b text-gray-800">{{ user.email }}</td>
            <td class="py-3 px-4 border-b capitalize text-gray-700">{{ user.role }}</td>
            <td class="py-3 px-4 border-b">
              <button
                @click="deleteUser(user.id)"
                class="bg-red-100 text-red-600 hover:bg-red-200 hover:text-red-700 font-medium px-3 py-1 rounded transition"
              >
                🗑 Hapus
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, onMounted } from 'vue'
//import { useRouter } from 'vue-router'
import api from '@/axios' // pastikan ini benar, sesuaikan dengan path `axios.js`

interface User {
  id: number
  name: string
  email: string
  role: string
}

const users = ref<User[]>([])
//const router = useRouter()

const fetchUsers = async () => {
  try {
    const response = await api.get('/users')
    console.log('DATA USER:', response.data)

    // Jika backend mengirim data dalam bentuk: { data: [...] }
    // Gunakan salah satu dari dua baris di bawah ini:
    // users.value = response.data.data  // jika ada 'data' di dalam response
    users.value = response.data        // jika response langsung berupa array user

  } catch (error) {
    console.error('Gagal mengambil data user', error)
  }
}

const deleteUser = async (id: number) => {
  if (!confirm('Yakin ingin menghapus user ini?')) return
  try {
    await api.delete(`/users/${id}`)
    users.value = users.value.filter(user => user.id !== id)
  } catch (error) {
    console.error('Gagal menghapus user', error)
  }
}

onMounted(fetchUsers)
</script>

<style scoped>
.container {
  max-width: 900px;
  margin: 0 auto;
  padding: 1rem;
}
</style>