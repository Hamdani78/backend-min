<template>
  <div class="wrapper">
    <!-- Preloader -->
    <div v-if="loading" class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__wobble" src="/admin/img/ic_logo.png" alt="AdminLogo" height="60" width="60" />
    </div>

    <!-- Header -->
    <Header />

    <!-- Sidebar -->
    <Sidebar />

    <!-- Main Content -->
    <main class="content-wrapper">
      <section class="content">
        <div class="container-fluid">
          <slot />
        </div>
      </section>
    </main>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark"></aside>

    <!-- Footer -->
    <Footer />
  </div>
</template>

<script setup>
import Header from './Header.vue'
import Sidebar from './Sidebar.vue'
import Footer from './Footer.vue'
import { ref, onMounted } from 'vue'

const loading = ref(true)
onMounted(() => loading.value = false)
</script>

<style>
.content-wrapper {
  min-height: calc(100vh - 57px);
  padding: 1rem;
}
</style>
