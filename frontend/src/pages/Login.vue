<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
    <div class="w-full max-w-md bg-white shadow-md rounded-xl p-8">
      <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Login</h2>
      <form @submit.prevent="handleLogin" class="space-y-4">
        <input
          v-model="email"
          type="email"
          placeholder="Email"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
        <input
          v-model="password"
          type="password"
          placeholder="Password"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
        <button
          type="submit"
          class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition"
        >
          Login
        </button>
      </form>
      <p class="text-center text-sm text-gray-600 mt-4">
        Belum punya akun?
        <router-link to="/register" class="text-blue-600 hover:underline">
          Daftar di sini
        </router-link>
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const email = ref('')
const password = ref('')
const router = useRouter()

const handleLogin = async () => {
  try {
    const res = await axios.post('http://localhost:8000/api/auth/login', {
      email: email.value,
      password: password.value
    })
    localStorage.setItem('user', JSON.stringify(res.data.user))
    localStorage.setItem('token', res.data.token)
    router.push('/dashboard')
  } catch (err) {
    alert('Login gagal: ' + err)
  }
}
</script>