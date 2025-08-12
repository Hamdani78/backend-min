<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { router } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'

const props = defineProps({
  pendaftars: Array
})

const submit = (p) => {
  router.post(route('spk.store'), {
    pendaftar_id: p.id,
    umur: p.umur,
    zonasi: p.zonasi,
    berkas: p.berkas,
    wawancara: p.wawancara
  })
}

const resetNilai = (p) => {
  p.umur = null
  p.zonasi = null
  p.berkas = null
  p.wawancara = null
}

/* ===== Pagination (frontend) ===== */
const page = ref(1)
const perPage = ref(10)

const total = computed(() => props.pendaftars?.length ?? 0)
const totalPages = computed(() => Math.max(1, Math.ceil(total.value / perPage.value)))
const startIndex = computed(() => (page.value - 1) * perPage.value)
const endIndex = computed(() => Math.min(startIndex.value + perPage.value, total.value))
const pagedRows = computed(() => (props.pendaftars || []).slice(startIndex.value, endIndex.value))

watch([() => props.pendaftars, perPage], () => { page.value = 1 })

const pageItems = computed(() => {
  const t = totalPages.value
  const p = page.value
  if (t <= 7) return Array.from({ length: t }, (_, i) => i + 1)
  const items = [1, p - 1, p, p + 1, t].filter(
    (v, i, arr) => v >= 1 && v <= t && arr.indexOf(v) === i
  ).sort((a, b) => a - b)
  const out = []
  for (let i = 0; i < items.length; i++) {
    out.push(items[i])
    if (i < items.length - 1 && items[i + 1] - items[i] > 1) out.push('…')
  }
  return out
})
function goTo(p) {
  if (p < 1 || p > totalPages.value) return
  page.value = p
}

/* helper untuk cek nilai terisi (termasuk 0.0) */
const isSet = v => v !== null && v !== undefined && v !== ''
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

      <table class="min-w-full divide-y divide-gray-200 border border-gray-300 text-sm">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-4 py-2 border border-gray-300">Nama</th>
            <th class="px-4 py-2 border border-gray-300">Umur</th>
            <th class="px-4 py-2 border border-gray-300">Zonasi</th>
            <th class="px-4 py-2 border border-gray-300">Berkas</th>
            <th class="px-4 py-2 border border-gray-300">Wawancara</th>
            <th class="px-4 py-2 border border-gray-300">Status</th>
            <th class="px-4 py-2 border border-gray-300 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="p in pagedRows" :key="p.id" class="hover:bg-gray-50">
            <td class="border border-gray-200 px-4 py-2">{{ p.nama }}</td>

            <td class="border border-gray-200 px-4 py-2">
              <select v-model.number="p.umur"
                class="w-full border border-gray-300 rounded-md px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
                <option :value="undefined" disabled selected>Pilih...</option>
                <option :value="0.5">&gt; 6 tahun</option>
                <option :value="0.0">&lt; 6 tahun</option>
              </select>
            </td>

            <td class="border border-gray-200 px-4 py-2">
              <select v-model.number="p.zonasi"
                class="w-full border border-gray-300 rounded-md px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
                <option :value="undefined" disabled selected>Pilih...</option>
                <option :value="0.3">&lt; 1 km</option>
                <option :value="0.2">1 – 2 km</option>
                <option :value="0.1">&gt; 2 km</option>
              </select>
            </td>

            <td class="border border-gray-200 px-4 py-2">
              <select v-model.number="p.berkas"
                class="w-full border border-gray-300 rounded-md px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
                <option :value="undefined" disabled selected>Pilih...</option>
                <option :value="0.15">Lengkap</option>
                <option :value="0.10">Kurang 1</option>
                <option :value="0.05">Kurang 2</option>
              </select>
            </td>

            <td class="border border-gray-200 px-4 py-2">
              <select v-model.number="p.wawancara"
                class="w-full border border-gray-300 rounded-md px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
                <option :value="undefined" disabled selected>Pilih...</option>
                <option :value="0.05">Sangat Baik</option>
                <option :value="0.03">Baik</option>
                <option :value="0.01">Kurang Baik</option>
              </select>
            </td>

            <td class="border border-gray-200 px-4 py-2">
              <span
                v-if="isSet(p.umur) && isSet(p.zonasi) && isSet(p.berkas) && isSet(p.wawancara)"
                class="inline-block bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded"
              >
                Sudah Dinilai
              </span>
              <span
                v-else
                class="inline-block bg-red-100 text-red-700 text-xs font-semibold px-2 py-1 rounded"
              >
                Belum Dinilai
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
          <button
            class="px-3 py-1 border rounded disabled:opacity-50"
            :disabled="page === 1"
            @click="goTo(page - 1)"
          >
            ‹
          </button>

          <template v-for="p in pageItems" :key="`p-${p}`">
            <button
              v-if="typeof p === 'number'"
              class="px-3 py-1 border rounded"
              :class="p === page ? 'bg-gray-200 font-semibold' : 'bg-white hover:bg-gray-50'"
              @click="goTo(p)"
            >
              {{ p }}
            </button>
            <span v-else class="px-2">…</span>
          </template>

          <button
            class="px-3 py-1 border rounded disabled:opacity-50"
            :disabled="page === totalPages"
            @click="goTo(page + 1)"
          >
            ›
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
