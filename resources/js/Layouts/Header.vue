<template>
  <header class="bg-white shadow px-4 py-2 flex items-center justify-between">
    <!-- Kiri: Navigasi -->
    <nav class="flex items-center space-x-4">
      <button @click="toggleSidebar" class="text-gray-600 hover:text-blue-600 text-lg">
        <i class="fas fa-bars"></i>
      </button>
      <Link href="/admin/dashboard" class="text-gray-600 hover:text-blue-600">Home</Link>
    </nav>

    <!-- Kanan: Ikon-ikon -->
    <div class="flex items-center space-x-4">
      <!-- Fullscreen -->
      <button class="text-gray-600 hover:text-blue-600" @click="toggleFullScreen">
        <i class="fas fa-expand-arrows-alt"></i>
      </button>

      <!-- Control Sidebar -->
      <button class="text-gray-600 hover:text-blue-600">
        <i class="fas fa-th-large"></i>
      </button>
    </div>
  </header>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'

const showSearch = ref(false)
const showNotif = ref(false)

const toggleSearch = () => {
  showSearch.value = !showSearch.value
}

const toggleNotif = () => {
  showNotif.value = !showNotif.value
}

const toggleSidebar = () => {
  // Anda bisa menggunakan event bus atau pinia store jika perlu
  console.log('Sidebar toggle requested')
}

const toggleFullScreen = () => {
  if (!document.fullscreenElement) {
    document.documentElement.requestFullscreen()
  } else if (document.exitFullscreen) {
    document.exitFullscreen()
  }
}
</script>
