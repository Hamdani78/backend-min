<template>
  <aside class="w-64 bg-gray-900 text-white min-h-screen">
    <Link href="/user/dashboard" class="flex items-center px-4 py-4 border-b border-gray-700">
      <img src="@/Pages/Landing/assets/img/ic_logo.png" alt="Logo" class="w-8 h-8 rounded-full" />
      <span class="ml-2 font-light text-lg">Pendaftar</span>
    </Link>

    <div class="p-4 space-y-4">
      <NavLink href="/user/dashboard" icon="fas fa-home" label="Dashboard" />

      <SidebarSection icon="fas fa-folder" label="Formulir" v-model:open="openForm">
        <NavLink href="/user/pendaftaran" icon="far fa-circle" label="Data Diri" />
        <NavLink href="/user/uploadberkas" icon="far fa-circle" label="Upload Berkas" />
        <NavLink href="/user/daftarulang" icon="far fa-circle" label="Daftar Ulang" />
        <NavLink href="/user/pengumuman" icon="far fa-circle" label="Pengumuman" />
      </SidebarSection>

      <Link href="/user/logout" method="post" as="button"
        class="block w-full bg-red-600 hover:bg-red-700 py-2 px-4 rounded text-white text-center">
        Logout
      </Link>
    </div>
  </aside>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import NavLink from '@/Components/NavLink.vue'
import SidebarSection from '@/Components/SidebarSection.vue'

const openForm = ref(true)
</script>
