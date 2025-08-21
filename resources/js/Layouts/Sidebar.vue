<template>
  <aside class="w-64 bg-gray-900 text-white min-h-screen">
    <Link :href="route('admin.dashboard')" class="flex items-center gap-3 px-4 py-4 border-b border-gray-700">
      <img :src="logoSrc" alt="Admin Logo" class="w-8 h-8 rounded-full" />
      <span class="font-light text-lg">ADMIN</span>
    </Link>

    <div class="p-4 space-y-4">
      <!-- Search -->
      <div>
        <input
          type="text"
          placeholder="Search"
          class="w-full px-3 py-2 rounded bg-gray-800 text-white border border-gray-700
                 focus:outline-none focus:ring-2 focus:ring-indigo-500"
        />
      </div>

      <!-- Top links -->
      <NavLink :href="route('admin.dashboard')" icon="fas fa-tachometer-alt" label="Dashboard" :active="$page.url === '/admin/dashboard'" />
      <NavLink :href="route('users.index')" label="Users" icon="far fa-circle" :active="$page.url.startsWith('/admin/users')" />

      <!-- Tables -->
      <SidebarSection icon="fas fa-table" label="Tables" v-model:open="openTables">
        <NavLink :href="route('pegawai.index')"   label="Pegawai"   icon="far fa-circle" :active="$page.url.startsWith('/admin/pegawai')" />
        <NavLink :href="route('siswa.index')"     label="Siswa"     icon="far fa-circle" :active="$page.url.startsWith('/admin/siswa')" />
        <NavLink :href="route('fasilitas.index')" label="Fasilitas" icon="far fa-circle" :active="$page.url.startsWith('/admin/fasilitas')" />
        <NavLink :href="route('kegiatan.index')"  label="Kegiatan"  icon="far fa-circle" :active="$page.url.startsWith('/admin/kegiatan')" />
        <NavLink :href="route('content.index')"   label="Content"   icon="far fa-circle" :active="$page.url.startsWith('/admin/content')" />
      </SidebarSection>

      <!-- PPDB -->
      <SidebarSection icon="fas fa-table" label="PPDB" v-model:open="openPPDB">
        <!-- Pengaturan PPDB as NavLink + badge -->
        <NavLink
          :href="route('admin.settings.ppdb.edit')"
          label="Pengaturan PPDB"
          icon="far fa-circle"
          :active="$page.url.startsWith('/admin/settings/ppdb')"
        >
          <template #suffix>
            <span
              class="ml-auto text-xs px-2 py-0.5 rounded-full whitespace-nowrap"
              :class="isOpen ? 'bg-green-600 text-white' : 'bg-red-600 text-white'"
            >
              {{ isOpen ? 'Buka' : 'Tutup' }}
            </span>
          </template>
        </NavLink>

        <NavLink :href="route('pendaftar.index')"          label="Pendaftar"    icon="far fa-circle" :active="$page.url.startsWith('/admin/pendaftar')" />
        <NavLink :href="route('berkas-pendaftaran.index')" label="Berkas"       icon="far fa-circle" :active="$page.url.startsWith('/admin/berkas-pendaftaran')" />
        <NavLink :href="route('spk.index')"                label="SPK"          icon="far fa-circle" :active="$page.url.startsWith('/admin/spk')" />
        <NavLink :href="route('admin.daftar-ulang.index')" label="Daftar Ulang" icon="far fa-circle" :active="$page.url.startsWith('/admin/daftar-ulang')" />
      </SidebarSection>

      <!-- Logout -->
      <Link
        :href="route('admin.logout')"
        method="post"
        as="button"
        class="w-full text-center mt-6 bg-indigo-600 hover:bg-indigo-700 py-2 px-4 rounded text-white"
      >
        Logout
      </Link>
    </div>
  </aside>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import NavLink from '@/Components/NavLink.vue'
import SidebarSection from '@/Components/SidebarSection.vue'

const openTables = ref(true)
const openPPDB   = ref(true)

// status PPDB dari Inertia::share
const page   = usePage()
const isOpen = computed(() => !!page.props.ppdb?.is_open)

// logo
import logoUrl from '@/Pages/Landing/assets/img/ic_logo.png'
const logoSrc = logoUrl
</script>
