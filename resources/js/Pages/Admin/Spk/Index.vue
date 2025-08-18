<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { router } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'

const props = defineProps({
  // controller harus mengirim: id, nama, umur, zonasi, berkas, wawancara, nilai_spk
  pendaftars: { type: Array, default: () => [] }
})

/* ===== Helpers tampilan badge & label ===== */
const labelUmur = v =>
  v === 0.5 ? '> 6 tahun' :
  v === 0.0 ? '< 6 tahun' : '-'

const labelZonasi = v =>
  v === 0.3 ? '< 1 km' :
  v === 0.2 ? '1 – 2 km' :
  v === 0.1 ? '> 2 km' : '-'

const labelBerkas = v =>
  v === 0.15 ? 'Lengkap' :
  v === 0.10 ? 'Kurang 1' :
  v === 0.05 ? 'Kurang 2' : '-'

const chip = (v) =>
  'inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ' +
  (v ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
     : 'bg-gray-50 text-gray-500 border border-gray-200')

// Tooltip opsional (lihat “title” di template)
const scoreTitle = v => (v ?? null) !== null ? `Skor: ${Number(v).toFixed(2)}` : ''

/* ===== Submit hanya WAWANCARA ===== */
const submit = (p) => {
  router.post(route('spk.store'), {
    pendaftar_id: p.id,
    wawancara: p.wawancara ?? ''
  }, { preserveScroll: true })
}

const resetNilai = (p) => { p.wawancara = null }

/* ===== Pagination ===== */
const page = ref(1)
const perPage = ref(10)
const total = computed(() => props.pendaftars.length)
const totalPages = computed(() => Math.max(1, Math.ceil(total.value / perPage.value)))
const startIndex = computed(() => (page.value - 1) * perPage.value)
const endIndex = computed(() => Math.min(startIndex.value + perPage.value, total.value))
const pagedRows = computed(() => props.pendaftars.slice(startIndex.value, endIndex.value))
watch([() => props.pendaftars, perPage], () => { page.value = 1 })

const pageItems = computed(() => {
  const t = totalPages.value, p = page.value
  if (t <= 7) return Array.from({ length: t }, (_, i) => i + 1)
  const items = [1, p - 1, p, p + 1, t]
    .filter((v, i, a) => v >= 1 && v <= t && a.indexOf(v) === i)
    .sort((a, b) => a - b)
  const out = []
  for (let i = 0; i < items.length; i++) {
    out.push(items[i])
    if (i < items.length - 1 && items[i + 1] - items[i] > 1) out.push('…')
  }
  return out
})
function goTo(p) { if (p >= 1 && p <= totalPages.value) page.value = p }
</script>

<template>
  <AdminLayout title="SPK Calon Siswa">
    <div class="p-6 bg-white rounded shadow">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold">Input Nilai SPK Calon Siswa</h2>
        <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded"
                @click="router.visit(route('spk.hasil'))">
          Lihat Hasil SPK
        </button>
      </div>

      <div class="text-xs text-gray-600 mb-3">
        <strong>Catatan:</strong> Umur, Zonasi, dan Berkas dihitung <em>otomatis</em> dari data pendaftar & berkas.
        Admin hanya mengisi nilai <strong>Wawancara</strong>.
      </div>

      <table class="min-w-full divide-y divide-gray-200 border border-gray-300 text-sm">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-4 py-2 border border-gray-300">Nama</th>
            <th class="px-4 py-2 border border-gray-300">Umur (Auto)</th>
            <th class="px-4 py-2 border border-gray-300">Zonasi (Auto)</th>
            <th class="px-4 py-2 border border-gray-300">Berkas (Auto)</th>
            <th class="px-4 py-2 border border-gray-300">Wawancara</th>
            <th class="px-4 py-2 border border-gray-300">Status</th>
            <th class="px-4 py-2 border border-gray-300 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="p in pagedRows" :key="p.id" class="hover:bg-gray-50">
            <td class="border border-gray-200 px-4 py-2">
              <div class="font-medium">{{ p.nama }}</div>
              <div v-if="p.nilai_spk !== null && p.nilai_spk !== undefined" class="text-xs text-gray-500">
                Total saat ini: <strong>{{ Number(p.nilai_spk).toFixed(2) }}</strong>
              </div>
            </td>

            <!-- Umur (read-only, label saja) -->
            <td class="border border-gray-200 px-4 py-2">
              <div :class="chip(p.umur)" :title="scoreTitle(p.umur)">
                {{ labelUmur(p.umur) }}
              </div>
            </td>

            <!-- Zonasi (read-only, label saja) -->
            <td class="border border-gray-200 px-4 py-2">
              <div :class="chip(p.zonasi)" :title="scoreTitle(p.zonasi)">
                {{ labelZonasi(p.zonasi) }}
              </div>
            </td>

            <!-- Berkas (read-only, label saja) -->
            <td class="border border-gray-200 px-4 py-2">
              <div :class="chip(p.berkas)" :title="scoreTitle(p.berkas)">
                {{ labelBerkas(p.berkas) }}
              </div>
            </td>

            <!-- Wawancara (editable) -->
            <td class="border border-gray-200 px-4 py-2">
              <select v-model.number="p.wawancara"
                      class="w-full border border-gray-300 rounded-md px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
                <option :value="undefined" disabled>Pilih...</option>
                <option :value="0.05">Sangat Baik (0.05)</option>
                <option :value="0.03">Baik (0.03)</option>
                <option :value="0.01">Kurang Baik (0.01)</option>
              </select>
            </td>

            <td class="border border-gray-200 px-4 py-2">
              <span v-if="p.wawancara !== null && p.wawancara !== undefined && p.wawancara !== ''"
                    class="inline-block bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded">
                Wawancara Terisi
              </span>
              <span v-else class="inline-block bg-red-100 text-red-700 text-xs font-semibold px-2 py-1 rounded">
                Menunggu Wawancara
              </span>
            </td>

            <td class="border border-gray-200 px-4 py-2 text-center">
              <button @click="submit(p)"
                      class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded text-sm shadow-sm">
                Simpan
              </button>
              <button @click="resetNilai(p)"
                      class="ml-2 bg-gray-300 hover:bg-gray-400 text-gray-800 px-3 py-1.5 rounded text-sm shadow-sm">
                Reset
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Paginator -->
      <div class="flex items-center justify-between mt-4">
        <div class="text-sm text-gray-600">
          Showing {{ startIndex + 1 }} to {{ endIndex }} of {{ total }} entries
        </div>

        <div class="inline-flex items-center gap-1">
          <button class="px-3 py-1 border rounded disabled:opacity-50"
                  :disabled="page === 1" @click="goTo(page - 1)">‹</button>

          <template v-for="p in pageItems" :key="`p-${p}`">
            <button v-if="typeof p === 'number'"
                    class="px-3 py-1 border rounded"
                    :class="p === page ? 'bg-gray-200 font-semibold' : 'bg-white hover:bg-gray-50'"
                    @click="goTo(p)">{{ p }}</button>
            <span v-else class="px-2">…</span>
          </template>

          <button class="px-3 py-1 border rounded disabled:opacity-50"
                  :disabled="page === totalPages" @click="goTo(page + 1)">›</button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
