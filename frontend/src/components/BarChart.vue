<template>
  <div>
    <Bar :data="chartData" :options="chartOptions" />
  </div>
</template>

<script>
import { Bar } from 'vue-chartjs';
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale
} from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

export default {
  name: 'BarChart',
  components: { Bar },
  props: {
    summary: {
      type: Object,
      required: true,
    },
  },
  computed: {
    chartData() {
      return {
        labels: ['Total Project', 'Total Tugas', 'Tugas Selesai', 'Progres %'],
        datasets: [
          {
            label: 'Statistik',
            backgroundColor: ['#3B82F6', '#FBBF24', '#10B981', '#8B5CF6'],
            data: [
              this.summary.total_projects,
              this.summary.total_tasks,
              this.summary.completed_tasks,
              this.summary.progress_percentage,
            ],
            borderRadius: 8,
          },
        ],
      };
    },
    chartOptions() {
      return {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1,
            },
          },
        },
      };
    },
  },
};
</script>

<style scoped>
div {
  height: 300px;
}
</style>