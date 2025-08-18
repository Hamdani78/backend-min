<template>
  <div class="layout min-h-screen bg-gray-100">
    <aside class="fixed inset-y-0 left-0 w-[var(--sidebar-w)] bg-white shadow z-30 overflow-y-auto">
      <Sidebar />
    </aside>

    <header class="fixed top-0 left-[var(--sidebar-w)] right-0 h-[var(--header-h)] bg-white shadow z-20">
      <Header />
    </header>

    <main
      class="pl-[var(--sidebar-w)] pt-[var(--header-h)] pb-[var(--footer-h)] h-screen overflow-y-auto"
    >
      <div class="p-4">
        <slot />
      </div>
    </main>

    <footer class="fixed bottom-0 left-[var(--sidebar-w)] right-0 h-[var(--footer-h)] bg-white shadow z-20">
      <Footer />
    </footer>
  </div>
</template>

<script setup>
import Sidebar from './Sidebar.vue'
import Header from './Header.vue'
import Footer from './Footer.vue'
</script>

<style scoped>
.layout {
  --sidebar-w: 16rem;  
  --header-h: 64px;     
  --footer-h: 56px;  
}

@media (max-width: 1024px) {
  .layout { --sidebar-w: 14rem; }
}
@media (max-width: 640px) {
  .layout { --sidebar-w: 0; }
}
</style>
