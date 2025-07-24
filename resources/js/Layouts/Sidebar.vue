<template>
  <aside class="w-64 bg-gray-900 text-white min-h-screen">
    <Link href="/admin/dashboard" class="flex items-center px-4 py-4 border-b border-gray-700">
      <img src="../Pages/Landing/assets/img/ic_logo.png" alt="Admin Logo" class="w-8 h-8 rounded-full" />
      <span class="font-light text-lg">ADMIN</span>
    </Link>

    <div class="p-4 space-y-4">
      <div>
        <input
          type="text"
          placeholder="Search"
          class="w-full px-3 py-2 rounded bg-gray-800 text-white border border-gray-600 focus:outline-none"
        />
      </div>

      <NavLink href="/admin/dashboard" icon="fas fa-tachometer-alt" label="Dashboard" exact />

      <SidebarSection icon="fas fa-table" label="Tables" v-model:open="openTables">
        <NavLink href="/admin/pegawai" label="Pegawai" icon="far fa-circle" />
        <NavLink href="/admin/siswa" label="Siswa" icon="far fa-circle" />
        <NavLink href="/admin/fasilitas" label="Fasilitas" icon="far fa-circle" />
        <NavLink href="/admin/kegiatan" label="Kegiatan" icon="far fa-circle" />
      </SidebarSection>

      <SidebarSection icon="fas fa-table" label="PPDB" v-model:open="openPPDB">
        <NavLink href="/admin/pendaftar" label="Pendaftar" icon="far fa-circle" />
        <NavLink href="/admin/berkas" label="Berkas" icon="far fa-circle" />
        <NavLink href="/admin/spk" label="SPK" icon="far fa-circle" />
      </SidebarSection>

      <Link
        href="/admin/logout"
        method="post"
        as="button"
        class="w-full text-center mt-6 bg-blue-600 hover:bg-blue-700 py-2 px-4 rounded text-white"
      >
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

const openTables = ref(true)
const openPPDB = ref(true)
</script>
