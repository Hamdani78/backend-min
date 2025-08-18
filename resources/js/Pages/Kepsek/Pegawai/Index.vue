<template>
  <KepsekLayout>
    <div id="kepsek-pegawai" class="bg-white shadow rounded-lg p-6">
      <!-- Header + Search -->
      <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-xl font-semibold">Data Guru dan Tenaga Kependidikan</h1>

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
        <table id="kepsek-pegawai-table" data-no-dt class="min-w-full text-sm text-gray-700">
          <thead class="bg-gray-100 text-gray-700">
            <tr>
              <th class="px-4 py-2 cursor-pointer select-none" @click="toggleSort('nama')">
                <span class="inline-flex items-center gap-1">
                  Nama
                  <span class="text-gray-400">{{ sort.key === 'nama' ? (sort.dir === 'asc' ? '▲' : '▼') : '◇' }}</span>
                </span>
              </th>
              <th class="px-4 py-2 cursor-pointer select-none" @click="toggleSort('nip')">
                <span class="inline-flex items-center gap-1">
                  NIP
                  <span class="text-gray-400">{{ sort.key === 'nip' ? (sort.dir === 'asc' ? '▲' : '▼') : '◇' }}</span>
                </span>
              </th>
              <th class="px-4 py-2 cursor-pointer select-none" @click="toggleSort('bidang_ajar')">
                <span class="inline-flex items-center gap-1">
                  Bidang Studi
                  <span class="text-gray-400">{{ sort.key === 'bidang_ajar' ? (sort.dir === 'asc' ? '▲' : '▼') : '◇'
                    }}</span>
                </span>
              </th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="row in pageItems" :key="row.id ?? row.nip ?? row.nama"
              class="odd:bg-white even:bg-gray-50 hover:bg-emerald-50 transition-colors">
              <td class="px-4 py-2 border-t">{{ row.nama || '-' }}</td>
              <td class="px-4 py-2 border-t">{{ row.nip || '-' }}</td>
              <td class="px-4 py-2 border-t">{{ row.bidang_ajar || '-' }}</td>
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
  pegawai: { type: Array, default: () => [] }
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
  const data = props.pegawai
  if (!term) return data
  return data.filter(r =>
    ['nama', 'nip', 'bidang_ajar'].some(k => String(r?.[k] ?? '').toLowerCase().includes(term))
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
    // angka vs string
    const an = Number(av), bn = Number(bv)
    if (Number.isFinite(an) && Number.isFinite(bn)) {
      return dir === 'asc' ? an - bn : bn - an
    }
    return dir === 'asc' ? String(av).localeCompare(String(bv)) : String(bv).localeCompare(String(av))
  })
})

/* Pagination */
const pageSize = ref(10)
const page = ref(1)
watch([() => props.pegawai, q, pageSize], () => { page.value = 1 })

const totalFiltered = computed(() => sorted.value.length)
const totalPages = computed(() => Math.max(0, Math.ceil(totalFiltered.value / pageSize.value)))
const startIndex = computed(() => (totalFiltered.value === 0 ? 0 : (page.value - 1) * pageSize.value))
const endIndex = computed(() => Math.min(startIndex.value + pageSize.value, totalFiltered.value))
const pageItems = computed(() => sorted.value.slice(startIndex.value, endIndex.value))

const startDisplay = computed(() => (totalFiltered.value === 0 ? 0 : startIndex.value + 1))
const endDisplay = computed(() => endIndex.value)
</script>

<style scoped>
#kepsek-pegawai .dataTables_info {
  display: none !important;
}
</style>
