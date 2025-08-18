<template>
  <div class="flex bg-gray-100 min-h-screen">
    <Sidebar class="hidden md:block fixed inset-y-0 left-0 w-64 z-20" />

    <!-- Area kanan -->
    <div class="flex-1 flex flex-col md:ml-64">
      <Header class="fixed top-0 left-0 md:left-64 right-0 h-16 z-30" />

      <div class="pt-16 pb-16 flex-1 overflow-auto">
        <main class="p-4">
          <slot />
        </main>
      </div>

      <Footer class="fixed bottom-0 left-0 md:left-64 right-0 h-8 z-30 bg-white shadow" />
    </div>
  </div>
</template>

<script setup>
import Sidebar from './Sidebar.vue'
import Header from './Header.vue'
import Footer from './Footer.vue'
</script>
