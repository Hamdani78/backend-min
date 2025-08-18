<template>
  <KepsekLayout>
    <div class="p-6 space-y-8">
      <!-- Header -->
      <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
          Dashboard Kepala Sekolah
        </h1>
        <span
          class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-200"
        >
          <i class="fa-regular fa-calendar"></i>
          Tahun Ajaran {{ tahunAjaran }}
        </span>
      </div>

      <!-- KPI Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Total Pendaftar -->
        <div class="bg-white rounded-xl shadow ring-1 ring-gray-200 p-5">
          <div class="flex items-center justify-between">
            <h2 class="text-sm font-medium text-gray-600">Total Pendaftar</h2>
            <div class="h-10 w-10 grid place-items-center rounded-lg bg-blue-50 text-blue-600">
              <i class="fa-regular fa-users"></i>
            </div>
          </div>
          <p class="mt-2 text-3xl font-bold text-gray-900">
            {{ (totalPendaftar || 0).toLocaleString() }}
          </p>
          <p class="mt-1 text-xs text-gray-500">
            100% dari keseluruhan pendaftar
          </p>
        </div>

        <!-- Lulus -->
        <div class="bg-white rounded-xl shadow ring-1 ring-gray-200 p-5">
          <div class="flex items-center justify-between">
            <h2 class="text-sm font-medium text-gray-600">Lulus Seleksi</h2>
            <div class="h-10 w-10 grid place-items-center rounded-lg bg-emerald-50 text-emerald-600">
              <i class="fa-regular fa-circle-check"></i>
            </div>
          </div>
          <p class="mt-2 text-3xl font-bold text-emerald-600">
            {{ (jumlahLulus || 0).toLocaleString() }}
          </p>
          <p class="mt-1 text-xs text-gray-500">
            {{ persenLulus.toFixed(1) }}% dari total pendaftar
          </p>
          <div class="mt-3 h-1.5 bg-gray-100 rounded">
            <div class="h-full bg-emerald-500 rounded" :style="{ width: persenLulus + '%' }"></div>
          </div>
        </div>

        <!-- Tidak Lulus -->
        <div class="bg-white rounded-xl shadow ring-1 ring-gray-200 p-5">
          <div class="flex items-center justify-between">
            <h2 class="text-sm font-medium text-gray-600">Tidak Lulus</h2>
            <div class="h-10 w-10 grid place-items-center rounded-lg bg-rose-50 text-rose-600">
              <i class="fa-regular fa-circle-xmark"></i>
            </div>
          </div>
          <p class="mt-2 text-3xl font-bold text-rose-600">
            {{ (jumlahTidakLulus || 0).toLocaleString() }}
          </p>
          <p class="mt-1 text-xs text-gray-500">
            {{ persenTidakLulus.toFixed(1) }}% dari total pendaftar
          </p>
          <div class="mt-3 h-1.5 bg-gray-100 rounded">
            <div class="h-full bg-rose-500 rounded" :style="{ width: persenTidakLulus + '%' }"></div>
          </div>
        </div>
      </div>

      <!-- Statistik Zonasi -->
      <div class="bg-white rounded-xl shadow ring-1 ring-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-lg font-semibold text-gray-900">Statistik Berdasarkan Zonasi</h2>
          <span class="text-sm text-gray-500">
            Total: <span class="font-medium text-gray-900">{{ totalZonasi.toLocaleString() }}</span> pendaftar
          </span>
        </div>

        <div v-if="(statistikPerZonasi || []).length > 0" class="space-y-3">
          <div
            v-for="item in zonasiTersortir"
            :key="item.zonasi"
            class="flex items-center gap-4"
          >
            <div class="w-28 shrink-0 text-sm font-medium text-gray-700">
              Zonasi {{ item.zonasi }}
            </div>

            <div class="flex-1">
              <div class="h-2 w-full bg-gray-100 rounded overflow-hidden">
                <div
                  class="h-full bg-indigo-500 rounded"
                  :style="{ width: (item.total / (totalZonasi || 1)) * 100 + '%' }"
                ></div>
              </div>
            </div>

            <div class="w-32 text-right text-sm tabular-nums text-gray-700">
              {{ item.total.toLocaleString() }}
              <span class="text-gray-400">
                ({{ (((item.total / (totalZonasi || 1)) * 100) || 0).toFixed(1) }}%)
              </span>
            </div>
          </div>
        </div>

        <div v-else class="text-sm text-gray-500">
          Belum ada data zonasi yang tersedia.
        </div>
      </div>
    </div>
  </KepsekLayout>
</template>

<script setup>
import KepsekLayout from '../../Layouts/KepsekLayout.vue'
import { computed } from 'vue'

const props = defineProps([
  'totalPendaftar',
  'jumlahLulus',
  'jumlahTidakLulus',
  'statistikPerZonasi'
])

/* Tahun ajaran (cutoff Juli) */
const tahunAjaran = computed(() => {
  const now = new Date()
  const y = now.getFullYear()
  const m = now.getMonth() + 1
  return m >= 7 ? `${y}/${y + 1}` : `${y - 1}/${y}`
})

/* Rasio lulus / tidak lulus */
const total = computed(() => Number(props.totalPendaftar || 0))
const persenLulus = computed(() =>
  total.value ? (Number(props.jumlahLulus || 0) / total.value) * 100 : 0
)
const persenTidakLulus = computed(() =>
  total.value ? (Number(props.jumlahTidakLulus || 0) / total.value) * 100 : 0
)

/* Zonasi */
const zonasiTersortir = computed(() => {
  const arr = Array.isArray(props.statistikPerZonasi) ? [...props.statistikPerZonasi] : []
  // sort terbesar ke terkecil
  return arr.sort((a, b) => (Number(b.total || 0) - Number(a.total || 0)))
})
const totalZonasi = computed(() =>
  zonasiTersortir.value.reduce((sum, z) => sum + Number(z.total || 0), 0)
)
</script>
