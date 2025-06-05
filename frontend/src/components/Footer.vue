<template>
  <footer class="bg-white border-t mt-auto">
    <div class="container mx-auto px-4 py-4 text-center text-sm text-gray-500">
      &copy; {{ year }} LaraSina Team · All rights reserved
    </div>
  </footer>
</template>

<script setup lang="ts">
const year = new Date().getFullYear()
</script>

<style scoped>
/* Tambahkan style tambahan jika perlu */
</style>