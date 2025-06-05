<template>
  <nav class="bg-white shadow-sm border-b border-gray-200">
    <div class="container mx-auto px-6 py-3 flex justify-between items-center">
      <!-- Logo / Judul -->
      <div class="text-2xl font-extrabold text-gray-900 tracking-wide">
        <router-link to="/dashboard" class="hover:text-blue-600 transition">
          LaraSina PM
        </router-link>
      </div>

      <!-- Menu Navigasi -->
      <div class="flex space-x-8 text-gray-700 font-medium text-base">
        <router-link
          to="/dashboard"
          class="hover:text-blue-600 transition"
          active-class="text-blue-600 border-b-2 border-blue-600 pb-1"
        >
          🏠Beranda
        </router-link>
        <router-link
          to="/projects"
          class="hover:text-blue-600 transition"
          active-class="text-blue-600 border-b-2 border-blue-600 pb-1"
        >
          📁Project
        </router-link>
        <router-link
          to="/profile"
          class="hover:text-blue-600 transition"
          active-class="text-blue-600 border-b-2 border-blue-600 pb-1"
        >
          👤Profil
        </router-link>
        <router-link
          v-if="user?.role === 'Admin'"
          to="/users"
          class="hover:text-blue-600 transition"
          active-class="text-blue-600 border-b-2 border-blue-600 pb-1"
        >
          👥Pengguna
        </router-link>
      </div>

      <!-- User Info -->
      <div class="text-gray-600 text-sm select-none flex items-center space-x-2">
        <span>👋</span>
        <span class="font-semibold">{{ user?.name || 'Guest' }}</span>
      </div>
    </div>
  </nav>
</template>

<script setup lang="ts">
//import { useRouter } from 'vue-router'
import { ref, onMounted } from 'vue'

//const router = useRouter()
const user = ref<{ name: string; role: string } | null>(null)

onMounted(() => {
  const userData = localStorage.getItem('user')
  if (userData) {
    try {
      user.value = JSON.parse(userData)
    } catch {
      user.value = null
    }
  }
})
</script>

<style scoped>
/* Tambahan kecil jika ingin memperhalus active link (tailwind sudah sangat cukup) */
</style>