<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref, computed, onMounted } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import axios from 'axios'

const hasil = ref([])
const filterStatus = ref('all')
const loading = ref(false)
const publishing = ref(false)

const page = usePage()
const flashSuccess = computed(() => page.props.flash?.success)
const flashError = computed(() => page.props.flash?.error)

const filteredHasil = computed(() => {
  const rows = [...hasil.value]
  // urutkan dari nilai terbesar
  rows.sort((a, b) => b.nilai - a.nilai)
  if (filterStatus.value === 'all') return rows
  return rows.filter(r => r.status === filterStatus.value)
})

async function loadHasil () {
  try {
    loading.value = true
    const res = await axios.get(route('spk.proses'))
    // pastikan nilai numerik
    hasil.value = (res.data || []).map(r => ({
      ...r,
      nilai: Number(r.nilai)
    }))
  } catch (e) {
    console.error('Gagal memuat hasil SPK:', e)
  } finally {
    loading.value = false
  }
}

function cetakPDF () {
  window.open(route('spk.pdf'), '_blank')
}

function exportExcel () {
  window.location.href = route('spk.excel')
}

function applyResults () {
  if (!confirm('Umumkan hasil ke database? Ini akan mengisi kolom “nilai_spk” & “status_lulus” pendaftar.')) return
  publishing.value = true
  router.post(route('admin.spk.terapkan'), {}, {
    preserveScroll: true,
    onSuccess: () => loadHasil(),
    onFinish: () => { publishing.value = false }
  })
}

onMounted(loadHasil)
</script>

<template>
  <AdminLayout title="Hasil SPK">
    <div class="p-6 bg-white rounded shadow max-w-4xl mx-auto">
      <h1 class="text-xl font-bold mb-4 text-center">Hasil Perangkingan SPK</h1>

      <!-- Flash -->
      <div v-if="flashSuccess" class="bg-green-100 text-green-800 p-3 rounded mb-4 text-sm">
        {{ flashSuccess }}
      </div>
      <div v-if="flashError" class="bg-red-100 text-red-800 p-3 rounded mb-4 text-sm">
        {{ flashError }}
      </div>

      <!-- Toolbar -->
      <div class="flex flex-wrap justify-between items-center mb-4 gap-2">
        <div class="space-x-2">
          <button @click="cetakPDF"
                  class="bg-red-600 hover:bg-red-700 text-white px-4 py-1.5 rounded text-sm"
                  :disabled="loading || publishing">
            Cetak PDF
          </button>
          <button @click="exportExcel"
                  class="bg-green-600 hover:bg-green-700 text-white px-4 py-1.5 rounded text-sm"
                  :disabled="loading || publishing">
            Export Excel
          </button>
          <button @click="applyResults"
                  class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-1.5 rounded text-sm disabled:opacity-60"
                  :disabled="loading || publishing || hasil.length === 0">
            <span v-if="!publishing">Umumkan Hasil</span>
            <span v-else>Memproses…</span>
          </button>
          <button @click="router.visit(route('spk.index'))"
                  class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-1.5 rounded text-sm"
                  :disabled="publishing">
            Kembali
          </button>
        </div>

        <div>
          <label class="text-sm font-medium mr-2">Filter:</label>
          <select v-model="filterStatus"
                  class="border border-gray-300 rounded px-2 py-1 text-sm focus:ring focus:ring-blue-300"
                  :disabled="loading || publishing">
            <option value="all">Semua</option>
            <option value="Lulus">Lulus</option>
            <option value="Tidak Lulus">Tidak Lulus</option>
          </select>
        </div>
      </div>

      <!-- Tabel -->
      <div v-if="loading" class="text-center text-sm text-gray-500 py-6">Memuat data…</div>
      <table v-else class="min-w-full divide-y divide-gray-200 border border-gray-300 text-sm">
        <thead class="bg-gray-100 text-left">
          <tr>
            <th class="border px-4 py-2">Peringkat</th>
            <th class="border px-4 py-2">Nama</th>
            <th class="border px-4 py-2">Nilai</th>
            <th class="border px-4 py-2">Status</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(s, index) in filteredHasil" :key="`${s.id}-${index}`" class="hover:bg-gray-50">
            <td class="border px-4 py-2">{{ index + 1 }}</td>
            <td class="border px-4 py-2">{{ s.nama }}</td>
            <td class="border px-4 py-2">{{ s.nilai.toFixed(2) }}</td>
            <td class="border px-4 py-2">
              <span :class="s.status === 'Lulus' ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold'">
                {{ s.status }}
              </span>
            </td>
          </tr>
          <tr v-if="filteredHasil.length === 0">
            <td colspan="4" class="text-center text-gray-500 py-6">Tidak ada data.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </AdminLayout>
</template>
