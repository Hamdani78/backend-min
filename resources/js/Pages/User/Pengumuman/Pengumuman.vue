<template>
  <UserLayout>
    <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
      <h1 class="text-2xl font-bold mb-4">Pengumuman Hasil Seleksi</h1>

      <!-- Status Kelulusan -->
      <div v-if="statusPengumuman === 'lulus'" class="bg-green-100 text-green-800 p-4 rounded text-center font-semibold">
        🎉 Selamat! Anda dinyatakan <span class="underline">LULUS</span>.

        <div class="mt-4">
          <button @click="router.visit('/user/daftar-ulang')"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
            Lanjut ke Daftar Ulang
          </button>
        </div>
      </div>

      <div v-else-if="statusPengumuman === 'tidak_lulus'" class="bg-red-100 text-red-700 p-4 rounded text-center font-semibold">
        ❌ Mohon maaf, Anda <span class="underline">TIDAK LULUS</span> tahun ini.
      </div>

      <div v-else class="bg-gray-100 text-gray-700 p-4 rounded text-center italic">
        Hasil seleksi belum diumumkan. Silakan kembali lagi nanti.
      </div>

      <!-- Informasi Pendaftar -->
      <div class="mt-6 text-sm text-gray-700" v-if="pendaftar">
        <p><strong>Nama:</strong> {{ pendaftar.nama }}</p>
        <p><strong>NIK:</strong> {{ pendaftar.nik }}</p>
        <p><strong>Nilai SPK:</strong> {{ nilaiSpk ?? '-' }}</p>
      </div>

      <!-- Tombol kembali -->
      <div class="mt-6">
        <button @click="router.visit('/user/dashboard')"
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
          Kembali ke Dashboard
        </button>
      </div>
    </div>
  </UserLayout>
</template>

<script setup>
import { usePage, router } from '@inertiajs/vue3'
import UserLayout from '@/Pages/User/UserLayouts/UserLayout.vue'

const page = usePage()
const pendaftar = page.props.pendaftar
const statusPengumuman = page.props.statusPengumuman
const nilaiSpk = page.props.nilaiSpk
</script>
