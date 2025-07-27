<!-- resources/js/Pages/User/UserLayouts/UserLayout.vue -->
<template>
  <div class="flex min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <Sidebar class="hidden md:block" />

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
      <!-- Header -->
      <Header />

      <!-- Page Content -->
      <main class="flex-1 p-4 overflow-auto">
        <slot />
      </main>

      <!-- Footer -->
      <Footer />
    </div>
  </div>
</template>

<script setup>
import Sidebar from './Sidebar.vue'
import Header from './Header.vue'
import Footer from './Footer.vue'
</script>
