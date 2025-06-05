<template>
  <div class="p-6 max-w-xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Profil Saya</h1>

    <div v-if="loading" class="text-gray-500 flex items-center gap-2">
      <span class="animate-spin h-5 w-5 border-2 border-blue-500 border-t-transparent rounded-full"></span>
      Memuat data profil...
    </div>

    <div v-else-if="error" class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4">
      {{ error }}
    </div>

    <div v-else class="space-y-6">
      <div class="bg-white p-5 shadow-lg rounded-lg">
        <p class="text-gray-700"><strong>Nama:</strong> {{ user.name }}</p>
        <p class="text-gray-700"><strong>Email:</strong> {{ user.email }}</p>
        <p class="text-gray-700"><strong>Role:</strong> {{ user.role }}</p>
      </div>

      <div class="flex flex-wrap gap-4">
        <button
          @click="goToEditProfile"
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-all"
        >
          ✏️ Edit Profil
        </button>

        <button
          @click="goToEditPassword"
          class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition-all"
        >
          🔒 Ganti Password
        </button>

        <button
          @click="logout"
          class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-all"
        >
          🚪 Keluar
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import api from '@/axios';

export default {
  data() {
    return {
      user: null,
      loading: true,
      error: null,
    };
  },
  methods: {
    async fetchProfile() {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.get('/auth/me');
        this.user = res.data;
      } catch (err) {
        this.error = 'Gagal memuat data profil.';
        console.error(err);
      } finally {
        this.loading = false;
      }
    },
    logout() {
      api.post('/auth/logout')
        .then(() => {
          localStorage.removeItem('token');
          this.$router.push('/login');
        })
        .catch(() => {
          alert('Logout gagal. Silakan coba lagi.');
        });
    },
    goToEditProfile() {
      this.$router.push('/edit-profile'); // sesuaikan route-nya
    },
    goToEditPassword() {
      this.$router.push('/edit-password'); // sesuaikan route-nya
    },
  },
  created() {
    this.fetchProfile();
  },
};
</script>

<style scoped>
/* Styling opsional */
</style>