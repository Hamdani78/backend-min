<template>
  <KepsekLayout>
    <div class="p-6 space-y-8">
      <h1 class="text-2xl font-bold text-gray-800">Dashboard Kepala Sekolah</h1>

      <!-- Statistik Ringkas -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded shadow p-5">
          <h2 class="text-base font-semibold text-gray-600">Total Pendaftar</h2>
          <p class="text-3xl font-bold text-blue-600">{{ totalPendaftar.toLocaleString() }}</p>
        </div>
        <div class="bg-white rounded shadow p-5 border-l-4 border-green-500">
          <h2 class="text-base font-semibold text-gray-600">Lulus Seleksi</h2>
          <p class="text-3xl font-bold text-green-600">{{ jumlahLulus.toLocaleString() }}</p>
        </div>
        <div class="bg-white rounded shadow p-5 border-l-4 border-red-500">
          <h2 class="text-base font-semibold text-gray-600">Tidak Lulus</h2>
          <p class="text-3xl font-bold text-red-600">{{ jumlahTidakLulus.toLocaleString() }}</p>
        </div>
      </div>

      <!-- Statistik Zonasi -->
      <div class="bg-white rounded shadow p-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Statistik Berdasarkan Zonasi</h2>
        <ul v-if="statistikPerZonasi.length" class="space-y-2">
          <li
            v-for="item in statistikPerZonasi"
            :key="item.zonasi"
            class="text-sm text-gray-700"
          >
            <span class="font-medium">Zonasi {{ item.zonasi }}:</span>
            {{ item.total.toLocaleString() }} pendaftar
          </li>
        </ul>
        <p v-else class="text-sm text-gray-500">Belum ada data zonasi yang tersedia.</p>
      </div>
    </div>
  </KepsekLayout>
</template>

<script setup>
import KepsekLayout from '../../Layouts/KepsekLayout.vue'


defineProps(['totalPendaftar', 'jumlahLulus', 'jumlahTidakLulus', 'statistikPerZonasi'])
</script>
