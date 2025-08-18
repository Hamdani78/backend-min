<!-- resources/js/Pages/Admin/Pendaftar/Index.vue -->
<template>
  <AdminLayout>
    <div class="p-6">
      <!-- Flash -->
      <div v-if="flashText" class="bg-blue-100 text-blue-800 px-4 py-2 rounded mb-4">
        {{ flashText }}
      </div>

      <div class="bg-white rounded shadow p-4">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold">Data Pendaftar - Tahun Ajaran {{ currentYear }}/{{ nextYear }}</h3>

          <Link
            as="button"
            type="button"
            :href="route('pendaftar.create')"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded inline-flex items-center"
          >
            <i class="fa fa-plus mr-2"></i> Tambah
          </Link>
        </div>

        <table :key="rows.length" ref="dtRef" class="min-w-full table-auto border">
          <thead>
            <tr class="bg-gray-100 text-left">
              <th class="px-4 py-2 border">Nama</th>
              <th class="px-4 py-2 border">Tempat, Tanggal Lahir</th>
              <th class="px-4 py-2 border">Jenis Kelamin</th>
              <th class="px-4 py-2 border">Agama</th>
              <th class="px-4 py-2 border">Status</th>
              <th class="px-4 py-2 border">Catatan</th>
              <th class="px-4 py-2 border">Foto</th>
              <th class="px-4 py-2 border">Aksi</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="row in rows" :key="row.id" class="hover:bg-gray-50">
              <td class="px-4 py-2 border">
                <span class="truncate-1">{{ row.nama }}</span>
              </td>

              <td class="px-4 py-2 border">
                <span class="truncate-1">
                  {{ row.tempat_lahir }}, {{ formatTanggal(row.tanggal_lahir) }}
                </span>
              </td>

              <td class="px-4 py-2 border">
                {{ row.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
              </td>

              <td class="px-4 py-2 border">
                <span class="truncate-1">{{ row.agama || '-' }}</span>
              </td>

              <td class="px-4 py-2 border">
                <span
                  :class="row.is_verified
                    ? 'bg-green-100 text-green-800 border border-green-200'
                    : 'bg-yellow-100 text-yellow-800 border border-yellow-200'"
                  class="px-2 py-0.5 rounded text-xs"
                >
                  {{ row.is_verified ? 'Verified' : 'Pending' }}
                </span>
              </td>

              <td class="px-4 py-2 border">
                <span
                  v-if="row.verification_note"
                  class="text-xs text-yellow-800 bg-yellow-50 border border-yellow-200 px-2 py-0.5 rounded"
                >
                  Ada catatan
                </span>
                <span v-else class="text-xs text-gray-400">-</span>
              </td>

              <td class="px-4 py-2 border">
                <template v-if="row.foto">
                  <img
                    :src="fotoUrl(row.foto)"
                    alt="Foto"
                    class="foto-thumb border"
                    loading="eager"
                    decoding="async"
                    width="48"
                    height="48"
                  />
                </template>
                <span v-else class="text-gray-400 italic">Belum ada</span>
              </td>

              <td class="px-4 py-2 border">
                <div class="flex items-center justify-center gap-1 flex-wrap">
                  <!-- Verifikasi -->
                  <button
                    v-if="!row.is_verified"
                    @click="postVerify(row.id)"
                    class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded btn-xs"
                  >
                    Verify
                  </button>
                  <button
                    v-else
                    @click="postUnverify(row.id)"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-2 py-1 rounded btn-xs"
                  >
                    Unverify
                  </button>

                  <!-- Request Fix -->
                  <button
                    @click="openFix(row.id)"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded btn-xs"
                  >
                    Request&nbsp;Fix
                  </button>

                  <!-- CRUD -->
                  <Link
                    :href="route('pendaftar.show', row.id)"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded btn-xs"
                  >
                    <i class="fa fa-eye"></i>
                  </Link>
                  <Link
                    :href="route('pendaftar.edit', row.id)"
                    class="bg-yellow-400 hover:bg-yellow-500 text-white px-2 py-1 rounded btn-xs"
                  >
                    <i class="fa fa-edit"></i>
                  </Link>
                  <button
                    @click="hapus(row.id)"
                    class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded btn-xs"
                  >
                    <i class="fa fa-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Modal Request Fix -->
      <div v-if="modalOpen" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <div class="bg-white w-full max-w-lg rounded shadow p-4">
          <h3 class="font-semibold text-lg mb-2">Kirim Catatan Perbaikan</h3>
          <textarea
            v-model="note"
            rows="6"
            class="w-full border rounded p-2"
            placeholder="Jelaskan data apa yang perlu diperbaiki..."
          />
          <div class="flex justify-end gap-2 mt-3">
            <button @click="closeFix" class="px-3 py-1 rounded border">Batal</button>
            <button @click="postRequestFix" class="px-3 py-1 rounded bg-yellow-500 text-white hover:bg-yellow-600">
              Kirim
            </button>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { router, Link, usePage } from '@inertiajs/vue3'
import { computed, onMounted, onBeforeUnmount, nextTick, ref, watch } from 'vue'

const currentYear = new Date().getFullYear()
const nextYear = currentYear + 1

/* ===== Props & derived state ===== */
const props = defineProps({ pendaftars: { type: Array, default: () => [] } })
const localRows = ref([...props.pendaftars]) 
watch(
  () => props.pendaftars,
  (v) => { localRows.value = [...(v ?? [])] },
  { immediate: true }
)
const rows = computed(() => localRows.value)

const flashText = computed(() => {
  const f = usePage().props.value?.flash
  return f?.status ?? f?.success ?? null
})

/* ===== Verifikasi actions ===== */
const modalOpen = ref(false)
const currentId = ref(null)
const note = ref('')

function openFix(id) { currentId.value = id; note.value = ''; modalOpen.value = true }
function closeFix() { modalOpen.value = false; currentId.value = null; note.value = '' }

async function afterServerChange () {
  await router.reload({ only: ['pendaftars', 'flash'], preserveScroll: true })
  await nextTick()
  initDT()
}

function postVerify(id) {
  router.post(route('admin.pendaftar.verify', id), {}, {
    preserveScroll: true,
    onSuccess: afterServerChange
  })
}
function postUnverify(id) {
  router.post(route('admin.pendaftar.unverify', id), {}, {
    preserveScroll: true,
    onSuccess: afterServerChange
  })
}
function postRequestFix() {
  if (!currentId.value) return
  router.post(route('admin.pendaftar.request_fix', currentId.value), { verification_note: note.value }, {
    preserveScroll: true,
    onSuccess: () => { closeFix(); afterServerChange() }
  })
}

/* ===== DataTables ===== */
import DataTable from 'datatables.net-dt'
import 'datatables.net-dt/css/dataTables.dataTables.css'
import 'datatables.net-responsive-dt'
import 'datatables.net-responsive-dt/css/responsive.dataTables.css'
import 'datatables.net-buttons-dt'
import 'datatables.net-buttons-dt/css/buttons.dataTables.css'
import 'datatables.net-buttons/js/buttons.colVis.mjs'
import 'datatables.net-buttons/js/buttons.html5.mjs'
import 'datatables.net-buttons/js/buttons.print.mjs'
import JSZip from 'jszip'
window.JSZip = JSZip
import pdfMake from 'pdfmake/build/pdfmake'
import { vfs as pdfVfs } from 'pdfmake/build/vfs_fonts'
pdfMake.vfs = pdfVfs
window.pdfMake = pdfMake

const dtRef = ref(null)
let dt = null

function initDT () {
  if (!dtRef.value) return
  if (dt) { dt.destroy(); dt = null }
  // eslint-disable-next-line no-new
  dt = new DataTable(dtRef.value, {
    pageLength: 10,
    lengthChange: false,
    responsive: true,
    autoWidth: false,
    layout: { topStart: { buttons: ['copy', 'csv', 'excel', 'pdf', 'print', 'colvis'] } },
    columnDefs: [{ targets: [6, 7], orderable: false, searchable: false }],
  })
}

onMounted(async () => { await nextTick(); initDT() })
onBeforeUnmount(() => { if (dt) dt.destroy() })
watch(rows, async () => { await nextTick(); initDT() }) 

/* ===== Helpers ===== */
function fotoUrl(path) { return path?.startsWith('http') ? path : `/storage/${path}` }
function formatTanggal(iso) {
  if (!iso) return '-'
  try {
    return new Intl.DateTimeFormat('id-ID', { day: '2-digit', month: 'long', year: 'numeric' })
      .format(new Date(iso))
  } catch { return iso }
}

/* ===== Hapus (optimistic + reload) ===== */
function hapus(id) {
  if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) return

  // 1) stop DataTables dulu biar tidak bentrok dengan perubahan DOM
  if (dt) { dt.destroy(); dt = null }

  // 2) optimistic: langsung hilangkan baris di UI
  const prev = [...localRows.value]
  localRows.value = localRows.value.filter((r) => r.id !== id)

  // 3) panggil server
  router.delete(route('pendaftar.destroy', id), {
    preserveScroll: true,
    onSuccess: afterServerChange,
    onError: async () => {
      // rollback jika gagal
      localRows.value = prev
      await nextTick()
      initDT()
      alert('Gagal menghapus data.')
    }
  })
}
</script>

<style scoped>
:deep(table.dataTable){border-collapse:collapse!important;border-spacing:0!important;table-layout:fixed}
:deep(table.dataTable thead th), :deep(table.dataTable tbody td){padding:6px 10px;vertical-align:middle}
:deep(table.dataTable th:nth-child(1)){width:200px}
:deep(table.dataTable th:nth-child(2)){width:220px}
:deep(table.dataTable th:nth-child(3)){width:120px}
:deep(table.dataTable th:nth-child(4)){width:120px}
:deep(table.dataTable th:nth-child(5)){width:110px}
:deep(table.dataTable th:nth-child(6)){width:110px}
:deep(table.dataTable th:nth-child(7)){width:90px}
:deep(table.dataTable th:nth-child(8)){width:220px}

.foto-thumb{width:48px;height:48px;object-fit:cover;border-radius:8px}
.truncate-1{white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.btn-xs{padding:4px 6px;line-height:1}
</style>
