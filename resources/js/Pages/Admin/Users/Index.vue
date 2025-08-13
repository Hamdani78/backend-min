<template>
  <AdminLayout>
    <div class="p-6">
      <div v-if="$page.props.flash?.success" class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
        {{ $page.props.flash.success }}
      </div>

      <div class="bg-white rounded shadow p-4">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold">Data User</h3>

          <Link
            as="button"
            type="button"
            :href="route('users.create')"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded inline-flex items-center"
          >
            <i class="fa fa-plus mr-2"></i> Tambah
          </Link>
        </div>

        <!-- DataTables target -->
        <table ref="dtRef" id="usersTable" class="min-w-full table-auto border text-sm">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2 border text-left">Nama</th>
              <th class="px-4 py-2 border text-left">Email</th>
              <th class="px-4 py-2 border text-left">Role</th>
              <th class="px-4 py-2 border text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="u in rows" :key="u.id" class="hover:bg-gray-50">
              <td class="px-4 py-2 border"><span class="truncate-1">{{ u.name }}</span></td>
              <td class="px-4 py-2 border"><span class="truncate-1">{{ u.email }}</span></td>
              <td class="px-4 py-2 border capitalize"><span class="truncate-1">{{ u.role }}</span></td>
              <td class="px-4 py-2 border">
                <div class="flex justify-center gap-2">
                  <Link :href="`/admin/users/${u.id}/edit`" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded btn-xs">
                    <i class="fa fa-edit"></i>
                  </Link>
                  <button @click="hapus(u.id)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded btn-xs">
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
import { Link, router } from '@inertiajs/vue3'
import { computed, ref, onMounted, onBeforeUnmount, nextTick, watch } from 'vue'

const props = defineProps({ users: { type: Array, default: () => [] } })
const rows = computed(() => props.users ?? [])

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

// Excel/PDF deps 
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
    layout: { topStart: { buttons: ['copy','csv','excel','pdf','print','colvis'] } },
    columnDefs: [
      { targets: [3], orderable: false, searchable: false } 
    ]
  })
}

onMounted(async () => { await nextTick(); initDT() })
onBeforeUnmount(() => { if (dt) dt.destroy() })
watch(rows, async () => { await nextTick(); initDT() })

function hapus(id) {
  if (confirm('Hapus user ini?')) {
    router.delete(`/admin/users/${id}`, { onSuccess: () => nextTick().then(initDT) })
  }
}
</script>

<style scoped>
:deep(table.dataTable thead th),
:deep(table.dataTable tbody td) {
  padding: 6px 10px;
  vertical-align: middle;
}

/* Tabel fixed & lebar kolom */
:deep(table.dataTable) { table-layout: fixed; }
:deep(table.dataTable th:nth-child(1)) { width: 220px; } 
:deep(table.dataTable th:nth-child(2)) { width: 260px; } 
:deep(table.dataTable th:nth-child(3)) { width: 140px; } 
:deep(table.dataTable th:nth-child(4)) { width: 120px; }

/* Truncate teks */
.truncate-1 {
  display: block;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* Tombol kecil */
.btn-xs { padding: 4px 8px; line-height: 1; }
</style>
