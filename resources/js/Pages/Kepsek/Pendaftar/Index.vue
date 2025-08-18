<template>
  <KepsekLayout>
    <div id="kepsek-spk" class="bg-white shadow rounded-lg p-6">
      <!-- Header: Judul + Tahun Ajaran + Search -->
      <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
          <h1 class="text-xl font-semibold">Data Pendaftar dan Nilai SPK</h1>
        </div>
        <label class="flex items-center gap-2">
          <span class="text-sm text-gray-700">Search:</span>
          <input v-model.trim="q" type="text"
            class="w-56 rounded border border-gray-300 px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
            placeholder="ketik untuk mencari..." />
        </label>
      </div>
      <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <span
          class="inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs font-medium bg-indigo-50 text-indigo-700 border-indigo-200">
          <i class="fa-regular fa-calendar"></i>
          Tahun Ajaran {{ tahunAjaran }}
        </span>
      </div>
      <!-- Table -->
      <div class="overflow-x-auto rounded-lg ring-1 ring-gray-200">
        <table id="kepsek-spk-table" data-no-dt class="min-w-full text-sm text-gray-700">
          <thead class="bg-gray-100 text-left">
            <tr>
              <th class="px-4 py-2 cursor-pointer select-none" @click="toggleSort('nama')">
                <span class="inline-flex items-center gap-1">
                  Nama
                  <span class="text-gray-400">{{ sort.key === 'nama' ? (sort.dir === 'asc' ? '▲' : '▼') : '◇' }}</span>
                </span>
              </th>
              <th class="px-4 py-2 cursor-pointer select-none" @click="toggleSort('status_lulus')">
                <span class="inline-flex items-center gap-1">
                  Status Lulus
                  <span class="text-gray-400">{{ sort.key === 'status_lulus' ? (sort.dir === 'asc' ? '▲' : '▼') : '◇'
                    }}</span>
                </span>
              </th>
              <th class="px-4 py-2 text-right cursor-pointer select-none" @click="toggleSort('nilai_spk')">
                <span class="inline-flex w-full items-center justify-end gap-1">
                  Nilai SPK
                  <span class="text-gray-400">{{ sort.key === 'nilai_spk' ? (sort.dir === 'asc' ? '▲' : '▼') : '◇' }}</span>
                </span>
              </th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="row in pageItems" :key="row.id"
              class="odd:bg-white even:bg-gray-50 transition-colors hover:bg-emerald-50">
              <td class="border-t px-4 py-2">{{ row.nama }}</td>

              <td class="border-t px-4 py-2">
                <span class="inline-flex items-center gap-2 rounded-full border px-2.5 py-0.5 text-xs font-medium"
                  :class="badgeClass(row.status_lulus)">
                  <span class="h-2 w-2 rounded-full" :class="dotClass(row.status_lulus)"></span>
                  {{ displayStatus(row.status_lulus) }}
                </span>
              </td>

              <td class="border-t px-4 py-2 text-right">
                {{ formatNilai(row.nilai_spk) }}
              </td>
            </tr>

            <tr v-if="totalFiltered === 0">
              <td colspan="3" class="px-4 py-10 text-center text-gray-500">No matching records found</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Footer (auto-hide pagination when single page) -->
      <div v-if="totalFiltered > 0" class="mt-3 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-gray-600">
          Showing
          <span class="font-medium text-gray-900">{{ startDisplay }}</span>
          to
          <span class="font-medium text-gray-900">{{ endDisplay }}</span>
          of
          <span class="font-medium text-gray-900">{{ totalFiltered }}</span>
          entries
        </p>

        <div v-if="totalPages > 1" class="inline-flex items-center gap-1">
          <button class="rounded border px-2.5 py-1.5 text-sm disabled:opacity-50" :disabled="page === 1"
            @click="page = 1">«</button>
          <button class="rounded border px-2.5 py-1.5 text-sm disabled:opacity-50" :disabled="page === 1"
            @click="page--">‹</button>

          <input type="number" v-model.number="page" :min="1" :max="totalPages || 1"
            class="w-14 rounded border py-1.5 text-center text-sm" />
          <span class="text-sm text-gray-600">/ {{ totalPages || 1 }}</span>

          <button class="rounded border px-2.5 py-1.5 text-sm disabled:opacity-50" :disabled="page === totalPages"
            @click="page++">›</button>
          <button class="rounded border px-2.5 py-1.5 text-sm disabled:opacity-50" :disabled="page === totalPages"
            @click="page = totalPages">»</button>
        </div>
      </div>
    </div>
  </KepsekLayout>
</template>

<script setup>
import KepsekLayout from '@/Layouts/KepsekLayout.vue'
import { computed, ref, watch } from 'vue'

const props = defineProps({
  pendaftar: { type: Array, default: () => [] }
})

/* Tahun ajaran (cutoff Juli) */
const tahunAjaran = computed(() => {
  const now = new Date()
  const y = now.getFullYear()
  const m = now.getMonth() + 1
  return m >= 7 ? `${y}/${y + 1}` : `${y - 1}/${y}`
})

/* Search & Sort */
const q = ref('')
const sort = ref({ key: 'nama', dir: 'asc' })
function toggleSort(key) {
  sort.value = sort.value.key === key
    ? { key, dir: sort.value.dir === 'asc' ? 'desc' : 'asc' }
    : { key, dir: 'asc' }
}

const filtered = computed(() => {
  const term = q.value.toLowerCase()
  const data = props.pendaftar
  if (!term) return data
  return data.filter(r =>
    ['nama', 'status_lulus', 'nilai_spk'].some(k => String(r[k] ?? '').toLowerCase().includes(term))
  )
})

const sorted = computed(() => {
  const arr = [...filtered.value]
  const { key, dir } = sort.value
  return arr.sort((a, b) => {
    const av = a?.[key], bv = b?.[key]
    if (av == null && bv == null) return 0
    if (av == null) return dir === 'asc' ? -1 : 1
    if (bv == null) return dir === 'asc' ? 1 : -1
    if (typeof av === 'number' && typeof bv === 'number') return dir === 'asc' ? av - bv : bv - av
    return dir === 'asc' ? String(av).localeCompare(String(bv)) : String(bv).localeCompare(String(av))
  })
})

/* Pagination */
const pageSize = ref(10)
const page = ref(1)
watch([() => props.pendaftar, q, pageSize], () => { page.value = 1 })

const totalFiltered = computed(() => sorted.value.length)
const totalPages = computed(() => Math.max(0, Math.ceil(totalFiltered.value / pageSize.value)))
const startIndex = computed(() => (totalFiltered.value === 0 ? 0 : (page.value - 1) * pageSize.value))
const endIndex = computed(() => Math.min(startIndex.value + pageSize.value, totalFiltered.value))
const pageItems = computed(() => sorted.value.slice(startIndex.value, endIndex.value))

const startDisplay = computed(() => (totalFiltered.value === 0 ? 0 : startIndex.value + 1))
const endDisplay = computed(() => endIndex.value)

/* Utilities tampilan */
function badgeClass(status) {
  const s = String(status || '').toLowerCase()
  if (s === 'lulus') return 'bg-green-50 text-green-700 border-green-200'
  if (s === 'tidak lulus' || s === 'tidak_lulus') return 'bg-red-50 text-red-700 border-red-200'
  return 'bg-gray-50 text-gray-600 border-gray-200'
}
function dotClass(status) {
  const s = String(status || '').toLowerCase()
  if (s === 'lulus') return 'bg-green-500'
  if (s === 'tidak lulus' || s === 'tidak_lulus') return 'bg-red-500'
  return 'bg-gray-400'
}
function displayStatus(status) {
  return String(status ?? '-').replace('_', ' ')
}
function formatNilai(n) {
  const num = Number(n)
  if (!Number.isFinite(num)) return '-'
  return num.toFixed(2)
}
</script>

<style scoped>
#kepsek-spk .dataTables_info {
  display: none !important;
}
</style>
