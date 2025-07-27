<template>
  <header class="bg-white shadow px-4 py-3 flex items-center justify-between">
    <div class="flex items-center gap-3">
      <button @click="toggleSidebar" class="text-gray-600 hover:text-blue-600 text-xl md:hidden">
        <i class="fas fa-bars"></i>
      </button>
      <Link href="/user/dashboard" class="text-lg font-semibold text-gray-700 hover:text-blue-600">Home</Link>
    </div>

    <div class="flex items-center gap-4">
      <button class="text-gray-600 hover:text-blue-600" @click="toggleFullScreen">
        <i class="fas fa-expand-arrows-alt"></i>
      </button>
    </div>
  </header>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

const toggleSidebar = () => {
  console.log('Sidebar toggle requested') // future enhancement
}

const toggleFullScreen = () => {
  if (!document.fullscreenElement) {
    document.documentElement.requestFullscreen()
  } else {
    document.exitFullscreen()
  }
}
</script>
