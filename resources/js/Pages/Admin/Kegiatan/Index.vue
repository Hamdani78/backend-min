<template>
  <AdminLayout title="Data Kegiatan">
    <!-- Flash Message -->
    <div v-if="$page.props.flash.success" class="bg-blue-100 text-blue-800 p-4 rounded mb-4">
      {{ $page.props.flash.success }}
    </div>

    <div class="bg-white p-6 rounded shadow">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold">Data Table Kegiatan</h2>

        <!-- Link as button (SPA) -->
        <Link
          as="button"
          type="button"
          :href="route('kegiatan.create')"
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded inline-flex items-center"
        >
          <i class="fa fa-plus mr-2"></i> Tambah
        </Link>
      </div>

      <!-- DataTables target -->
      <table ref="dtRef" id="kegiatanTable" class="min-w-full table-auto border text-sm">
        <thead>
          <tr class="bg-gray-100 text-left">
            <th class="px-4 py-2 border">Nama</th>
            <th class="px-4 py-2 border">Deskripsi</th>
            <th class="px-4 py-2 border">Foto</th>
            <th class="px-4 py-2 border">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in rows" :key="item.id" class="hover:bg-gray-50">
            <td class="px-4 py-2 border"><span class="truncate-1">{{ item.nama }}</span></td>
            <td class="px-4 py-2 border"><span class="truncate-1">{{ item.deskripsi }}</span></td>
            <td class="px-4 py-2 border">
              <Link
                as="button"
                type="button"
                :href="route('kegiatanimage.index', { kegiatanId: item.id })"
                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded inline-flex items-center"
              >
                <i class="fa fa-image mr-1"></i> Tambah Foto
              </Link>
            </td>
            <td class="px-4 py-2 border">
              <div class="flex gap-2">
                <Link :href="route('kegiatan.edit', item.id)" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded btn-xs">
                  <i class="fa fa-edit"></i>
                </Link>
                <button @click="hapus(item.id)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded btn-xs">
                  <i class="fa fa-trash"></i>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { router, Link } from '@inertiajs/vue3'
import { computed, onMounted, onBeforeUnmount, nextTick, ref, watch } from 'vue'

const props = defineProps({ kegiatan: { type: Object, default: () => ({ data: [] }) } })
/* dukung array langsung atau paginator Laravel */
const rows = computed(() => Array.isArray(props.kegiatan?.data) ? props.kegiatan.data : (props.kegiatan || []))

function hapus(id) {
  if (confirm('Apakah Anda yakin ingin menghapus kegiatan ini?')) {
    router.delete(route('kegiatan.destroy', id))
  }
}

/* ===== DataTables (ESM) – sama dengan Pegawai/Siswa ===== */
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
let dt

function initDT () {
  if (dt) dt.destroy()
  dt = new DataTable(dtRef.value, {
    pageLength: 10,
    lengthChange: false,
    responsive: true,
    autoWidth: false,
    layout: { topStart: { buttons: ['copy','csv','excel','pdf','print','colvis'] } },
    columnDefs: [
      { targets: [2, 3], orderable: false },   // Foto & Aksi tak bisa sort
      { targets: [2, 3], searchable: false }   // Foto & Aksi tak dicari
    ]
  })
}

onMounted(async () => { await nextTick(); initDT() })
onBeforeUnmount(() => { if (dt) dt.destroy() })
watch(rows, async () => { await nextTick(); initDT() })
</script>

<style scoped>
/* bikin garis vertikal muncul rapi */
:deep(table.dataTable){
  border-collapse: collapse !important;
  border-spacing: 0 !important;
  table-layout: fixed;
}

/* compact cells (match Pegawai/Siswa) */
:deep(table.dataTable thead th),
:deep(table.dataTable tbody td){
  padding: 6px 10px;
  vertical-align: middle;
}

/* lebar kolom */
:deep(table.dataTable th:nth-child(1)) { width: 220px; } /* Nama */
:deep(table.dataTable th:nth-child(2)) { width: 320px; } /* Deskripsi */
:deep(table.dataTable th:nth-child(3)) { width: 140px; } /* Foto */
:deep(table.dataTable th:nth-child(4)) { width: 140px; } /* Aksi */

/* utils */
.truncate-1{ white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display:block; }
.btn-xs{ padding: 4px 8px; line-height: 1; }
</style>
