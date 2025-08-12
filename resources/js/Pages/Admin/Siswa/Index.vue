<template>
  <AdminLayout>
    <div>
      <!-- Flash -->
      <div v-if="flashSuccess" class="bg-blue-100 text-blue-800 p-4 rounded mb-4">
        {{ flashSuccess }}
      </div>

      <div class="bg-white rounded shadow p-4">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold">Data Siswa - Tahun Ajaran {{ currentYear }}/{{ nextYear }}
          </h3>

          <!-- Link as button (SPA) -->
          <Link
            as="button"
            type="button"
            :href="route('siswa.create')"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded inline-flex items-center"
          >
            <i class="fa fa-plus mr-2"></i> Tambah
          </Link>
        </div>

        <!-- DataTables target -->
        <table ref="dtRef" id="siswaTable" class="min-w-full table-auto border">
          <thead>
            <tr class="bg-gray-100 text-left">
              <th class="px-4 py-2 border">Kelas</th>
              <th class="px-4 py-2 border">Jumlah</th>
              <th class="px-4 py-2 border">Laki-laki</th>
              <th class="px-4 py-2 border">Perempuan</th>
              <th class="px-4 py-2 border">Wali Kelas</th>
              <th class="px-4 py-2 border">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="row in rows" :key="row.id" class="hover:bg-gray-50">
              <td class="px-4 py-2 border"><span class="truncate-1">{{ row.kelas }}</span></td>
              <td class="px-4 py-2 border">{{ row.jml_siswa }}</td>
              <td class="px-4 py-2 border">{{ row.laki_laki }}</td>
              <td class="px-4 py-2 border">{{ row.perempuan }}</td>
              <td class="px-4 py-2 border"><span class="truncate-1">{{ row.pegawai?.nama ?? '-' }}</span></td>
              <td class="px-4 py-2 border">
                <div class="flex gap-2">
                  <Link :href="route('siswa.edit', row.id)" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded btn-xs">
                    <i class="fa fa-edit"></i>
                  </Link>
                  <button @click="hapus(row.id)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded btn-xs">
                    <i class="fa fa-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { router, Link, usePage } from '@inertiajs/vue3'
import { computed, onMounted, onBeforeUnmount, nextTick, ref, watch } from 'vue'

const props = defineProps({
  siswa: { type: Object, default: () => ({ data: [] }) }
})
const rows = computed(() => Array.isArray(props.siswa?.data) ? props.siswa.data : (props.siswa || []))
const flashSuccess = computed(() => usePage().props.value?.flash?.success)

const currentYear = new Date().getFullYear()
const nextYear = currentYear + 1

/* ===== DataTables (ESM, tanpa jQuery) ===== */
import DataTable from 'datatables.net-dt'
import 'datatables.net-dt/css/dataTables.dataTables.css'
import 'datatables.net-responsive-dt'
import 'datatables.net-responsive-dt/css/responsive.dataTables.css'
import 'datatables.net-buttons-dt'
import 'datatables.net-buttons-dt/css/buttons.dataTables.css'
import 'datatables.net-buttons/js/buttons.colVis.mjs'
import 'datatables.net-buttons/js/buttons.html5.mjs'
import 'datatables.net-buttons/js/buttons.print.mjs'

// Excel/PDF deps (load sekali saja; aman bila di-load ulang)
import JSZip from 'jszip'
window.JSZip = window.JSZip || JSZip
import pdfMake from 'pdfmake/build/pdfmake'
import { vfs as pdfVfs } from 'pdfmake/build/vfs_fonts'
if (!window.pdfMake) {
  pdfMake.vfs = pdfVfs
  window.pdfMake = pdfMake
}

const dtRef = ref(null)
let dt

function initDT () {
  if (dt) dt.destroy()

  dt = new DataTable(dtRef.value, {
    pageLength: 10,
    lengthChange: false,
    responsive: true,
    autoWidth: false,
    layout: { topStart: { buttons: ['copy', 'csv', 'excel', 'pdf', 'print', 'colvis'] } },
    columnDefs: [
      { targets: [5], orderable: false, searchable: false } // Aksi
    ]
  })
}

onMounted(async () => { await nextTick(); initDT() })
onBeforeUnmount(() => { if (dt) dt.destroy() })
watch(rows, async () => { await nextTick(); initDT() })

function hapus(id) {
  if (confirm('Apakah Anda Yakin?')) {
    router.delete(route('siswa.destroy', id), { onSuccess: () => nextTick().then(initDT) })
  }
}
</script>

<style scoped>
/* Compact agar 10 baris muat 1 layar */
:deep(table.dataTable thead th),
:deep(table.dataTable tbody td) {
  padding: 6px 10px;
  vertical-align: middle;
}

/* Fix kolom + kurangi wrap */
:deep(table.dataTable) { table-layout: fixed; }
:deep(table.dataTable th:nth-child(1)) { width: 180px; } /* Kelas */
:deep(table.dataTable th:nth-child(2)) { width: 100px; } /* Jumlah */
:deep(table.dataTable th:nth-child(3)) { width: 110px; } /* Laki-laki */
:deep(table.dataTable th:nth-child(4)) { width: 120px; } /* Perempuan */
:deep(table.dataTable th:nth-child(5)) { width: 220px; } /* Wali Kelas */
:deep(table.dataTable th:nth-child(6)) { width: 120px; } /* Aksi */

.truncate-1 {
  display: block;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.btn-xs { padding: 4px 8px; line-height: 1; }
</style>
