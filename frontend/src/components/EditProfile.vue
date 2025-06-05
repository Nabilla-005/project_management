<template>
  <div class="p-6 max-w-xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Edit Profil</h1>

    <div v-if="loading" class="text-gray-500">Memuat profil...</div>
    <div v-else-if="error" class="text-red-600 mb-4">{{ error }}</div>
    <form @submit.prevent="updateProfile" class="space-y-4">
      <div>
        <label class="block mb-1 font-semibold" for="name">Nama</label>
        <input
          v-model="form.name"
          id="name"
          type="text"
          required
          class="w-full border rounded p-2"
        />
      </div>

      <div>
        <label class="block mb-1 font-semibold" for="email">Email</label>
        <input
          v-model="form.email"
          id="email"
          type="email"
          required
          class="w-full border rounded p-2"
        />
      </div>

      <button
        type="submit"
        :disabled="submitting"
        class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 disabled:opacity-50"
      >
        {{ submitting ? 'Updating...' : 'Update Profile' }}
      </button>
    </form>

    <div v-if="successMessage" class="mt-4 text-green-600">
      {{ successMessage }}
    </div>
  </div>
</template>

<script>
import api from '@/axios';

export default {
  data() {
    return {
      form: {
        name: '',
        email: '',
      },
      loading: true,
      error: null,
      submitting: false,
      successMessage: '',
    };
  },
  methods: {
    async fetchProfile() {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.get('/auth/me'); // sesuaikan endpoint kalau beda
        this.form.name = res.data.name;
        this.form.email = res.data.email;
      } catch (err) {
        this.error = 'Gagal memuat data profil.';
        console.error(err);
      } finally {
        this.loading = false;
      }
    },
    async updateProfile() {
      this.submitting = true;
      this.error = null;
      this.successMessage = '';
      try {
        const res = await api.put('/profile', {
          name: this.form.name,
          email: this.form.email,
        });
        this.successMessage = res.data.message || 'Profil berhasil diperbarui.';
      } catch (err) {
        if (err.response && err.response.data && err.response.data.message) {
          this.error = err.response.data.message;
        } else {
          this.error = 'Gagal memperbarui profil.';
        }
        console.error(err);
      } finally {
        this.submitting = false;
      }
    },
  },
  created() {
    this.fetchProfile();
  },
};
</script>

<style scoped>
/* Tambahkan styling kalau perlu */
</style>