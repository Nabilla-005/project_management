<template>
  <div class="p-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Ubah Profil & Password</h1>

    <form @submit.prevent="submitProfile" class="bg-white p-6 rounded shadow mb-8">
      <!-- Nama -->
      <div class="mb-4">
        <label class="block mb-1 font-semibold">Nama</label>
        <input
          type="text"
          v-model="profile.name"
          class="w-full border rounded px-3 py-2"
          required
        />
      </div>

      <!-- Email -->
      <div class="mb-4">
        <label class="block mb-1 font-semibold">Email</label>
        <input
          type="email"
          v-model="profile.email"
          class="w-full border rounded px-3 py-2"
          required
        />
      </div>

      <!-- Password Lama -->
      <div class="mb-4">
        <label class="block mb-1 font-semibold">Password Lama</label>
        <input
          type="password"
          v-model="profile.current_password"
          class="w-full border rounded px-3 py-2"
          :required="profile.password.length > 0"
        />
      </div>

      <!-- Password Baru -->
      <div class="mb-4">
        <label class="block mb-1 font-semibold">Password Baru</label>
        <input
          type="password"
          v-model="profile.password"
          class="w-full border rounded px-3 py-2"
          minlength="6"
        />
      </div>

      <!-- Konfirmasi Password -->
      <div class="mb-6">
        <label class="block mb-1 font-semibold">Konfirmasi Password Baru</label>
        <input
          type="password"
          v-model="profile.password_confirmation"
          class="w-full border rounded px-3 py-2"
          minlength="6"
        />
      </div>

      <!-- Feedback -->
      <div v-if="formError" class="mb-4 text-red-600">{{ formError }}</div>
      <div v-if="formSuccess" class="mb-4 text-green-600">{{ formSuccess }}</div>

      <!-- Submit -->
      <button
        type="submit"
        :disabled="loading"
        class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 disabled:opacity-50"
      >
        {{ loading ? 'Menyimpan...' : 'Ubah Password' }}
      </button>
    </form>
  </div>
</template>

<script>
import api from '@/axios';

export default {
  data() {
    return {
      profile: {
        name: '',
        email: '',
        current_password: '',
        password: '',
        password_confirmation: '',
      },
      loading: false,
      formError: null,
      formSuccess: null,
    };
  },
  async created() {
    try {
      const res = await api.get('/auth/me');
      this.profile.name = res.data.name;
      this.profile.email = res.data.email;
    } catch (e) {
      this.formError = 'Gagal memuat data user.';
    }
  },
  methods: {
    async submitProfile() {
      this.formError = null;
      this.formSuccess = null;

      if (
        this.profile.password &&
        this.profile.password !== this.profile.password_confirmation
      ) {
        this.formError = 'Konfirmasi password tidak cocok.';
        return;
      }

      this.loading = true;
      try {
        await api.put('/profile', this.profile);
        this.formSuccess = 'Profil berhasil diperbarui.';
        this.profile.current_password = '';
        this.profile.password = '';
        this.profile.password_confirmation = '';
      } catch (err) {
        this.formError = err.response?.data?.message || 'Terjadi kesalahan.';
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>