<template>
  <AdminLayout>
    <div class="p-6 bg-white shadow rounded">
      <h2 class="text-xl font-bold mb-4">Surat Pernyataan Daftar Ulang</h2>

      <table class="table-auto w-full text-sm border border-gray-300">
        <thead class="bg-gray-100">
          <tr>
            <th class="p-2 border">Nama</th>
            <th class="p-2 border">Surat Pernyataan</th>
            <th class="p-2 border">Status</th>
            <th class="p-2 border">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in pagedRows" :key="item.id" class="border">
            <td class="p-2 border">{{ item.pendaftar?.nama ?? '-' }}</td>

            <td class="p-2 border">
              <template v-if="item.file_surat">
                <a :href="`/storage/${item.file_surat}`" target="_blank" class="text-blue-600 underline">Lihat</a>
              </template>
              <span v-else class="text-gray-400 italic">-</span>
            </td>

            <td class="p-2 border space-x-2">
              <button
                v-if="item.status === 'dikirim'"
                @click="verifikasi(item.id)"
                :disabled="loadingId === item.id"
                class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 disabled:opacity-50"
              >
                Verifikasi
              </button>

              <button
                v-else-if="item.status === 'diverifikasi' && item.pendaftar?.status_pendaftaran !== 'selesai'"
                @click="selesaikan(item.id)"
                :disabled="loadingId === item.id"
                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded disabled:opacity-50"
              >
                Tandai Selesai
              </button>

              <span v-else-if="item.pendaftar?.status_pendaftaran === 'selesai'" class="text-green-700 font-semibold">
                Selesai
              </span>
            </td>

            <td class="p-2 border">
              <!-- (opsional) tempatkan aksi lain di sini -->
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

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { router } from '@inertiajs/vue3'
import { ref, computed, watch } from 'vue'

const props = defineProps({
  daftarUlang: Array
})

const loadingId = ref(null)

function verifikasi(id) {
  if (confirm('Verifikasi surat ini?')) {
    loadingId.value = id
    router.post(route('admin.daftar-ulang.verifikasi', id), {}, {
      onFinish: () => (loadingId.value = null)
    })
  }
}

function selesaikan(id) {
  if (confirm('Yakin ingin menandai pendaftar ini sebagai selesai?')) {
    loadingId.value = id
    router.post(route('admin.daftar-ulang.selesai', id), {}, {
      onFinish: () => (loadingId.value = null)
    })
  }
}

/* ===== Pagination (frontend) ===== */
const page = ref(1)
const perPage = ref(10)

const total = computed(() => props.daftarUlang?.length ?? 0)
const totalPages = computed(() => Math.max(1, Math.ceil(total.value / perPage.value)))
const startIndex = computed(() => (page.value - 1) * perPage.value)
const endIndex = computed(() => Math.min(startIndex.value + perPage.value, total.value))
const pagedRows = computed(() => (props.daftarUlang || []).slice(startIndex.value, endIndex.value))

watch([() => props.daftarUlang, perPage], () => { page.value = 1 })

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
</script>
