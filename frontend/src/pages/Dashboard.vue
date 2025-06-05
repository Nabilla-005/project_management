<template>
  <div class="min-h-screen bg-gray-100 py-10 px-4">
    <div class="max-w-5xl mx-auto">
      <!-- Header Welcome -->
      <div class="mb-8 text-center">
        <h1 class="text-4xl font-extrabold text-gray-800 mb-2 tracking-tight">
          Hallo, {{ user?.name || 'User' }} 👋
        </h1>
        <p class="text-lg text-gray-600">
          Selamat datang di <span class="font-semibold capitalize">{{ user?.role || '-' }}</span>'s Beranda
        </p>
      </div>

      <!-- Error / Loading -->
      <div v-if="loading" class="text-center text-gray-500 text-lg animate-pulse">
        Memuat data...
      </div>
      <div v-else-if="error" class="bg-red-100 text-red-600 p-4 rounded text-center mb-6 shadow">
        {{ error }}
      </div>

      <!-- Dashboard Content -->
      <div v-else>
        <!-- Bar Chart Card -->
        <div class="bg-white p-6 rounded-2xl shadow-md mb-10">
          <h2 class="text-2xl font-semibold text-gray-700 mb-6 border-b pb-2">
            📊 Ringkasan Project & Tugas
          </h2>
          <BarChart :summary="summary" />
        </div>

        <!-- Logout -->
      </div>
    </div>
  </div>
</template>

<script>
import api from '@/axios';
import BarChart from '@/components/BarChart.vue';
import App from '@/App.vue';

export default {
  components: { BarChart },
  data() {
    return {
      user: null,
      summary: {
        total_projects: 0,
        total_tasks: 0,
        completed_tasks: 0,
        progress_percentage: 0,
      },
      loading: true,
      error: null,
    };
  },
  methods: {
    async fetchDashboard() {
      this.loading = true;
      try {
        const [userRes, summaryRes] = await Promise.all([
          api.get('/auth/me'),
          api.get('/dashboard'),
        ]);
        this.user = userRes.data;
        this.summary = summaryRes.data;
      } catch (err) {
        this.error = 'Gagal memuat data dashboard.';
        console.error(err);
      } finally {
        this.loading = false;
      }
    },
  },
  created() {
    this.fetchDashboard();
  },
};
</script>