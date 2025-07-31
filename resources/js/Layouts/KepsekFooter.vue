<template>
  <footer class="bg-gray-100 border-t text-sm text-gray-600 py-3 px-6 flex justify-between">
    <span>
      &copy; {{ year }} 
      <a href="https://instagram.com/_fhmdni_" class="text-blue-600 hover:underline font-semibold" target="_blank">_fhmdni_</a>
    </span>
    <span class="font-semibold"></span>Version 1.0.0
  </footer>
</template>

<script setup>
const year = new Date().getFullYear()
</script>
