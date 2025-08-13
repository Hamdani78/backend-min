<template>
  <AdminLayout>
    <div>
      <div v-if="flashSuccess" class="bg-blue-100 text-blue-800 p-4 rounded mb-4">
        {{ flashSuccess }}
      </div>

      <div class="bg-white rounded shadow p-4">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold">
            Data Table Pegawai - Tahun Ajaran {{ currentYear }}/{{ nextYear }}
          </h3>

          <Link as="button" type="button" :href="route('pegawai.create')"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded inline-flex items-center">
            <i class="fa fa-plus mr-2"></i> Tambah
          </Link>
        </div>

        <!-- gunakan table-fixed agar kolom stabil -->
        <table ref="dtRef" id="example1" class="w-full table-fixed border">
          <thead>
            <tr class="bg-gray-100 text-left">
              <th class="px-4 py-2 border">Nama</th>
              <th class="px-4 py-2 border">NIP</th>
              <th class="px-4 py-2 border">Email</th>
              <th class="px-4 py-2 border">Bidang Ajar</th>
              <th class="px-4 py-2 border">Status</th>
              <th class="px-4 py-2 border text-center">Foto</th>
              <th class="px-4 py-2 border text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="data in pegawai" :key="data.id" class="hover:bg-gray-50">
              <td class="px-4 py-2 border">
                <span class="cell-truncate" :title="data.nama">{{ data.nama }}</span>
              </td>
              <td class="px-4 py-2 border">
                <span class="cell-truncate" :title="data.nip">{{ data.nip }}</span>
              </td>
              <td class="px-4 py-2 border">
                <span class="cell-truncate" :title="data.email">{{ data.email }}</span>
              </td>
              <td class="px-4 py-2 border">
                <span class="cell-truncate" :title="data.bidang_ajar">{{ data.bidang_ajar }}</span>
              </td>
              <td class="px-4 py-2 border">
                {{ data.is_active ? 'Aktif' : 'Non-aktif' }}
              </td>
              <td class="px-4 py-2 border text-center">
                <img :src="fotoUrl(data.foto)" alt="Foto" class="foto-thumb border" loading="lazy" />
              </td>
              <td class="px-4 py-2 border text-center">
                <div class="inline-flex gap-2">
                  <Link :href="route('pegawai.edit', data.id)"
                        class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded btn-xs">
                    <i class="fa fa-edit"></i>
                  </Link>
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
import { Link, usePage } from '@inertiajs/vue3'
import { computed, onMounted, onBeforeUnmount, nextTick, ref, watch } from 'vue'

const props = defineProps({ pegawai: { type: Array, default: () => [] } })
const flashSuccess = computed(() => usePage().props.value?.flash?.success)

const currentYear = new Date().getFullYear()
const nextYear = currentYear + 1

// ====== DataTables ======
import DataTable from 'datatables.net-dt'
import 'datatables.net-dt/css/dataTables.dataTables.css'
import 'datatables.net-responsive-dt'
import 'datatables.net-responsive-dt/css/responsive.dataTables.css'
import 'datatables.net-buttons-dt'
import 'datatables.net-buttons-dt/css/buttons.dataTables.css'
import 'datatables.net-buttons/js/buttons.colVis.mjs'
import 'datatables.net-buttons/js/buttons.html5.mjs'
import 'datatables.net-buttons/js/buttons.print.mjs'

// Excel/PDF deps
import JSZip from 'jszip'
window.JSZip = JSZip
import pdfMake from 'pdfmake/build/pdfmake'
import { vfs as pdfVfs } from 'pdfmake/build/vfs_fonts'
pdfMake.vfs = pdfVfs
window.pdfMake = pdfMake

const dtRef = ref(null)
let dt

function initDT() {
  if (dt) dt.destroy()

  dt = new DataTable(dtRef.value, {
    pageLength: 10,
    lengthChange: false,
    responsive: true,
    autoWidth: false,
    layout: { topStart: { buttons: ['copy', 'csv', 'excel', 'pdf', 'print', 'colvis'] } },
    columnDefs: [
      // 0..6 => Nama, NIP, Email, Bidang Ajar, Keaktifan, Foto, Aksi
      { targets: [5, 6], orderable: false },
      { targets: [5, 6], searchable: false },
      { targets: 0, width: '240px' },
      { targets: 1, width: '140px' },
      { targets: 2, width: '240px' },
      { targets: 3, width: '220px' },
      { targets: 4, width: '110px' },
      { targets: 5, width: '90px'  },
      { targets: 6, width: '110px' }
    ]
  })

  // pastikan hitung ulang lebar setelah render
  setTimeout(() => { dt.columns.adjust().responsive.recalc() }, 0)
}

onMounted(async () => {
  await nextTick()
  initDT()
})
onBeforeUnmount(() => { if (dt) dt.destroy() })
watch(() => props.pegawai, async () => { await nextTick(); initDT() })

function fotoUrl(file) {
  if (!file) return '/images/no-image.png'
  return file.startsWith('http') ? file : `/storage/pegawai/${file}`
}
</script>

<style scoped>
/* padding kompak + vertikal tengah */
:deep(table.dataTable thead th),
:deep(table.dataTable tbody td) {
  padding: 6px 10px;
  vertical-align: middle;
}

/* paksa layout stabil */
:deep(table.dataTable) {
  table-layout: fixed;
  border-collapse: collapse;
}

/* Header width (7 kolom) — keep in sync dgn columnDefs */
:deep(table.dataTable th:nth-child(1)) { width: 240px; } 
:deep(table.dataTable th:nth-child(2)) { width: 140px; } 
:deep(table.dataTable th:nth-child(3)) { width: 240px; } 
:deep(table.dataTable th:nth-child(4)) { width: 220px; }
:deep(table.dataTable th:nth-child(5)) { width: 110px; } 
:deep(table.dataTable th:nth-child(6)) { width: 90px;  } 
:deep(table.dataTable th:nth-child(7)) { width: 110px; } 

/* thumbnail foto */
.foto-thumb { width: 48px; height: 48px; object-fit: cover; border-radius: 8px; }

/* teks dipotong satu baris */
.cell-truncate { display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }

/* tombol kecil */
.btn-xs { padding: 4px 8px; line-height: 1; }
</style>
