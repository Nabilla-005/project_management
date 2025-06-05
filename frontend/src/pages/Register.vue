<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
    <div class="w-full max-w-md bg-white shadow-lg rounded-xl p-8">
      <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Register</h2>
      <form @submit.prevent="registerUser" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
          <input
            v-model="name"
            type="text"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input
            v-model="email"
            type="email"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <input
            v-model="password"
            type="password"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
          <input
            v-model="password_confirmation"
            type="password"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
          <select
            v-model="role"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
          >
            <option disabled value="">Pilih role</option>
            <option value="Admin">Admin</option>
            <option value="Project Manager">Project Manager</option>
            <option value="Team Member">Team Member</option>
          </select>
        </div>

        <div v-if="errorMessage" class="text-red-500 text-sm">
          {{ errorMessage }}
        </div>

        <div v-if="successMessage" class="text-green-600 text-sm">
          {{ successMessage }}
        </div>


        <button
          type="submit"
          class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition"
        >
          Register
        </button>
      </form>

      <p class="text-center text-sm text-gray-600 mt-4">
        Sudah punya akun?
        <router-link to="/login" class="text-blue-600 hover:underline">
          Login di sini
        </router-link>
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'
//import router from '@/router'

const name = ref('')
const email = ref('')
const password = ref('')
const password_confirmation = ref('')
const role = ref('')
const errorMessage = ref('')
const successMessage = ref('')

const registerUser = async () => {
  errorMessage.value = ''
  successMessage.value = ''

  if (password.value !== password_confirmation.value) {
    errorMessage.value = 'Password dan konfirmasi tidak cocok.'
    return
  }

  try {
    const response = await axios.post('http://localhost:8000/api/auth/register', {
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: password_confirmation.value,
      role: role.value,
    })

    // Jika berhasil
    localStorage.setItem('token', response.data.token)
    successMessage.value = 'Registrasi berhasil! Silakan login.'
    
    // Kosongkan form
    name.value = ''
    email.value = ''
    password.value = ''
    password_confirmation.value = ''
    role.value = ''
  } catch (error: any) {
    if (error.response?.data?.message) {
      errorMessage.value = 'Register gagal: ' + error.response.data.message
    } else {
      errorMessage.value = 'Terjadi kesalahan saat register.'
    }
  }
}
</script>