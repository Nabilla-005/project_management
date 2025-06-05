<template>
  <div id="app" class="min-h-screen bg-gray-100 text-gray-800">
    <router-view />
  </div>
</template>

<script>
export default {
  name: 'App',
};
</script>

<style>
/* Opsional tambahan jika perlu font global atau custom scrollbar */
body {
  margin: 0;
  font-family: 'Inter', sans-serif;
}
</style>