<!-- resources/js/Pages/Admin/Pendaftar/Index.vue -->
<template>
    <AdminLayout>
        <div class="p-6">
            <div v-if="flashText" class="bg-blue-100 text-blue-800 px-4 py-2 rounded mb-4">
                {{ flashText }}
            </div>

            <div class="bg-white rounded shadow p-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Data Pendaftar</h3>

                    <Link as="button" type="button" :href="route('pendaftar.create')"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded inline-flex items-center">
                    <i class="fa fa-plus mr-2"></i> Tambah
                    </Link>
                </div>

                <table ref="dtRef" id="pendaftarTable" class="min-w-full table-auto border">
                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="px-4 py-2 border">Nama</th>
                            <th class="px-4 py-2 border">Tempat, Tanggal Lahir</th>
                            <th class="px-4 py-2 border">Jenis Kelamin</th>
                            <th class="px-4 py-2 border">Agama</th>
                            <th class="px-4 py-2 border">Foto</th>
                            <th class="px-4 py-2 border">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="row in rows" :key="row.id" class="hover:bg-gray-50">
                            <td class="px-4 py-2 border"><span class="truncate-1">{{ row.nama }}</span></td>
                            <td class="px-4 py-2 border">
                                <span class="truncate-1">{{ row.tempat_lahir }}, {{ formatTanggal(row.tanggal_lahir)
                                }}</span>
                            </td>
                            <td class="px-4 py-2 border">{{ row.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </td>
                            <td class="px-4 py-2 border"><span class="truncate-1">{{ row.agama }}</span></td>
                            <td class="px-4 py-2 border">
                                <template v-if="row.foto">
                                    <img :src="fotoUrl(row.foto)" alt="Foto" class="foto-thumb border" loading="lazy" />
                                </template>
                                <span v-else class="text-gray-400 italic">Belum ada</span>
                            </td>
                            <td class="px-4 py-2 border">
                                <div class="flex items-center justify-center gap-1">
                                    <Link :href="route('pendaftar.show', row.id)"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded btn-xs">
                                    <i class="fa fa-eye"></i>
                                    </Link>
                                    <Link :href="route('pendaftar.edit', row.id)"
                                        class="bg-yellow-400 hover:bg-yellow-500 text-white px-2 py-1 rounded btn-xs">
                                    <i class="fa fa-edit"></i>
                                    </Link>
                                    <button @click="hapus(row.id)"
                                        class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded btn-xs">
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

const props = defineProps({ pendaftars: { type: Array, default: () => [] } })
const rows = computed(() => props.pendaftars ?? [])
const flashText = computed(() => {
    const f = usePage().props.value?.flash
    return f?.status ?? f?.success ?? null
})

/* DataTables */
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

function initDT() {
    if (dt) dt.destroy()
    dt = new DataTable(dtRef.value, {
        pageLength: 10,
        lengthChange: false,
        responsive: true,
        autoWidth: false,
        layout: { topStart: { buttons: ['copy', 'csv', 'excel', 'pdf', 'print', 'colvis'] } },
        columnDefs: [
            { targets: [4, 5], orderable: false, searchable: false }
        ]
    })
}

onMounted(async () => { await nextTick(); initDT() })
onBeforeUnmount(() => { if (dt) dt.destroy() })
watch(rows, async () => { await nextTick(); initDT() })

function fotoUrl(path) { return path?.startsWith('http') ? path : `/storage/${path}` }
function hapus(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        router.delete(route('pendaftar.destroy', id), { onSuccess: () => nextTick().then(initDT) })
    }
}
function formatTanggal(iso) {
    if (!iso) return '-'
    try {
        return new Intl.DateTimeFormat('id-ID', { day: '2-digit', month: 'long', year: 'numeric' }).format(new Date(iso))
    } catch { return iso }
}
</script>

<style scoped>
/* match Pegawai: compact + rapi */
:deep(table.dataTable) {
    border-collapse: collapse !important;
    border-spacing: 0 !important;
    table-layout: fixed;
}

:deep(table.dataTable thead th),
:deep(table.dataTable tbody td) {
    padding: 6px 10px;
    vertical-align: middle;
}

/* Lebar kolom */
:deep(table.dataTable th:nth-child(1)) {
    width: 220px;
}

:deep(table.dataTable th:nth-child(2)) {
    width: 280px;
}

:deep(table.dataTable th:nth-child(3)) {
    width: 140px;
}

:deep(table.dataTable th:nth-child(4)) {
    width: 140px;
}

:deep(table.dataTable th:nth-child(5)) {
    width: 90px;
}

:deep(table.dataTable th:nth-child(6)) {
    width: 160px;
}

.foto-thumb {
    width: 48px;
    height: 48px;
    object-fit: cover;
    border-radius: 8px;
}

.truncate-1 {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.btn-xs {
    padding: 4px 6px;
    line-height: 1;
}

</style>
