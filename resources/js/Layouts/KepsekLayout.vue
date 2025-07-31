<template>
  <div class="min-h-screen flex bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-900 text-white min-h-screen shadow-md">
      <Link href="/kepsek/dashboard" class="flex items-center px-4 py-4 border-b border-gray-700">
        <img src="@/Pages/Landing/assets/img/ic_logo.png" alt="Logo" class="w-8 h-8 mr-3 rounded-full" />
        <span class="text-lg font-light">KEPSEK</span>
      </Link>

      <div class="p-4 space-y-4">
        <NavLink href="/kepsek/dashboard" icon="fas fa-tachometer-alt" label="Dashboard" exact />
        
        <NavLink :href="route('kepsek.pegawai.index')" icon="fas fa-users" label="Data Pegawai" />
        <NavLink :href="route('kepsek.pendaftar.index')" icon="fas fa-users" label="Data Pendaftar" />

        <Link href="/user/logout" method="post" as="button"
          class="w-full text-center mt-6 bg-red-600 hover:bg-red-700 py-2 px-4 rounded text-white">
          Logout
        </Link>
      </div>
    </aside>

    <!-- Main Area -->
    <div class="flex-1 flex flex-col">
      <KepsekHeader />

      <main class="flex-1 p-6">
        <slot />
      </main>

      <KepsekFooter />
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import NavLink from '../Layouts/NavLink.vue'
import KepsekHeader from '../Layouts/KepsekHeader.vue'
import KepsekFooter from '../Layouts/KepsekFooter.vue'
</script>
