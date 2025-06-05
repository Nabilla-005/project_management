<template>
  <div class="p-6 max-w-3xl mx-auto">
    <h2 class="text-3xl font-bold mb-6 text-center text-gray-800">
      💬 Komentar untuk Tugas: {{ taskTitle }}
    </h2>

    <!-- Tombol kembali -->
    <div class="mb-4">
      <button
        @click="goBack"
        class="text-blue-600 hover:underline text-sm flex items-center gap-1"
      >
        ← Kembali
      </button>
    </div>

    <!-- Daftar Komentar -->
    <div v-if="comments.length" class="space-y-4 mb-8">
      <div
        v-for="comment in comments"
        :key="comment.id"
        class="border border-gray-200 bg-white rounded-lg shadow-sm p-4 hover:shadow transition"
      >
        <p class="text-gray-800">{{ comment.content }}</p>
        <div class="text-xs text-gray-500 mt-2">
          Oleh <span class="font-medium">{{ comment.user.name }}</span> pada
          <span class="italic">{{ formatDate(comment.created_at) }}</span>
        </div>
      </div>
    </div>
    <div v-else class="text-gray-500 italic mb-6 text-center">Belum ada komentar.</div>

    <!-- Form Tambah Komentar -->
    <form @submit.prevent="submitComment" class="space-y-4 bg-gray-50 p-5 rounded-lg shadow">
      <textarea
        v-model="newComment"
        rows="4"
        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 resize-none"
        placeholder="Tulis komentar Anda..."
        required
      ></textarea>
      <div class="flex justify-end">
        <button
          type="submit"
          class="bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-700 transition font-semibold shadow"
          :disabled="loading"
        >
          Kirim Komentar
        </button>
      </div>
    </form>

    <!-- Pesan error -->
    <div v-if="errorMsg" class="mt-4 text-red-600 font-semibold text-center">
      {{ errorMsg }}
    </div>

    <!-- Toast sukses -->
    <transition name="fade">
      <div
        v-if="showToast"
        class="fixed bottom-6 right-6 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50"
      >
        Komentar berhasil ditambahkan! 🎉
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from '@/axios'
import echo from '@/echo'  // pastikan sudah buat dan export echo di src/echo.ts

const route = useRoute()
const router = useRouter()
const taskId = route.params.id

const comments = ref([])
const taskTitle = ref('')
const newComment = ref('')
const errorMsg = ref('')
const loading = ref(false)
const showToast = ref(false)

const fetchTaskAndComments = async () => {
  try {
    const [taskRes, commentRes] = await Promise.all([
      axios.get(`/tasks/${taskId}`),
      axios.get(`/tasks/${taskId}/comments`)
    ])
    taskTitle.value = taskRes.data.title
    comments.value = commentRes.data
  } catch (err) {
    console.error('Gagal memuat data:', err)
    errorMsg.value = 'Gagal memuat komentar atau tugas.'
  }
}

const submitComment = async () => {
  errorMsg.value = ''
  if (!newComment.value.trim()) {
    errorMsg.value = 'Komentar tidak boleh kosong.'
    return
  }

  loading.value = true
  try {
    const res = await axios.post(`/tasks/${taskId}/comments`, {
      content: newComment.value
    })
    comments.value.push(res.data)
    newComment.value = ''

    // Tampilkan toast
    showToast.value = true
    setTimeout(() => (showToast.value = false), 2500)
  } catch (err) {
    console.error('Gagal mengirim komentar:', err)
    errorMsg.value = 'Gagal mengirim komentar.'
  } finally {
    loading.value = false
  }
}

const formatDate = (dateStr) => {
  const date = new Date(dateStr)
  return date.toLocaleString('id-ID', {
    dateStyle: 'medium',
    timeStyle: 'short'
  })
}

const goBack = () => {
  router.back()
}

// --- Tambahkan ini untuk listen event realtime ---
function handleNewComment(data) {
  // Cek apakah komentar sudah ada supaya gak duplikat
  if (!comments.value.some(c => c.id === data.id)) {
    comments.value.push(data)

    // Opsional: munculkan toast juga kalau komen realtime masuk
    showToast.value = true
    setTimeout(() => (showToast.value = false), 2500)
  }
}

onMounted(() => {
  fetchTaskAndComments()

  // Subscribe channel private untuk task ini
  echo.private(`tugas.${taskId}`)
    .listen('KomentarDitambahkan', handleNewComment)
})

onBeforeUnmount(() => {
  // Leave channel biar ga boros resource
  echo.leave(`private-tugas.${taskId}`)
})
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>