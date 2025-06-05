<template>
  <div class="p-6 max-w-7xl mx-auto">
    <!-- Tombol Kembali -->
    <div class="mb-4">
      <router-link
        :to="`/projects/${projectId}/tasks`"
        class="inline-block text-blue-600 hover:text-blue-800 font-semibold transition"
      >
        ← Kembali ke Daftar Tugas
      </router-link>
    </div>

    <h1 class="text-3xl font-bold mb-6 text-center">📋 Kanban Board - Project: {{ projectName }}</h1>

    <div v-if="loading" class="text-center text-gray-600">Memuat data...</div>

    <div v-else class="flex gap-6">
      <div
        v-for="status in statuses"
        :key="status"
        class="kanban-column flex-1"
        @dragover.prevent
        @drop="handleDrop($event, status)"
      >
        <h2 class="text-xl font-semibold mb-4 text-center">{{ status }}</h2>

        <div
          v-for="task in tasksByStatus(status)"
          :key="task.id"
          draggable="true"
          @dragstart="handleDragStart(task)"
          class="kanban-task"
          :title="task.description"
        >
          <div class="font-semibold">{{ task.title }}</div>
          <div class="text-xs text-gray-500 mt-1">Assigned to: {{ task.assigned?.name || '-' }}</div>
        </div>

        <div v-if="tasksByStatus(status).length === 0" class="text-center text-gray-400 mt-10">
          Tidak ada tugas
        </div>
      </div>
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
const tasks = ref([])
const loading = ref(true)
const draggedTask = ref(null)
const statuses = ['To Do', 'In Progress', 'Done']

const fetchProject = async () => {
  try {
    const res = await axios.get(`/projects/${projectId}`)
    projectName.value = res.data.name
  } catch (error) {
    console.error('Gagal ambil data project:', error)
  }
}

const fetchTasks = async () => {
  try {
    const res = await axios.get(`/projects/${projectId}/tasks`)
    tasks.value = res.data
  } catch (error) {
    console.error('Gagal ambil data tugas:', error)
  }
}

onMounted(async () => {
  loading.value = true
  await Promise.all([fetchProject(), fetchTasks()])
  loading.value = false
})

const tasksByStatus = (status) => {
  return tasks.value.filter(task => task.status === status)
}

const handleDragStart = (task) => {
  draggedTask.value = task
}

const handleDrop = async (event, newStatus) => {
  if (!draggedTask.value) return
  if (draggedTask.value.status === newStatus) return

  try {
    await axios.put(`/tasks/${draggedTask.value.id}`, { status: newStatus })

    const idx = tasks.value.findIndex(t => t.id === draggedTask.value.id)
    if (idx !== -1) {
      tasks.value[idx].status = newStatus
    }
    draggedTask.value = null
  } catch (error) {
    alert('Gagal update status tugas')
    console.error(error)
  }
}
</script>

<style scoped>
.kanban-column {
  background-color: #f9fafb;
  border-radius: 8px;
  padding: 1rem;
  min-height: 350px;
  box-shadow: 0 0 5px rgb(0 0 0 / 0.05);
  display: flex;
  flex-direction: column;
}

.kanban-task {
  background: white;
  margin-bottom: 1rem;
  padding: 0.75rem 1rem;
  border-radius: 6px;
  box-shadow: 0 1px 3px rgb(0 0 0 / 0.1);
  cursor: grab;
  user-select: none;
  transition: background-color 0.2s ease;
}

.kanban-task:active {
  cursor: grabbing;
  background-color: #e0f2fe;
}
</style>