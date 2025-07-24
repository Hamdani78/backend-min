<template>
    <AdminLayout title="Data Kegiatan">
        <!-- Flash Message -->
        <div v-if="$page.props.flash.success" class="bg-blue-100 text-blue-800 p-4 rounded mb-4">
            {{ $page.props.flash.success }}
        </div>

        <!-- Card Container -->
        <div class="bg-white p-6 rounded shadow">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Data Table Kegiatan</h2>
                <Link :href="route('kegiatan.create')">
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    <i class="fa fa-plus mr-2"></i> Tambah
                </button>
                </Link>
            </div>

            <!-- Table -->
            <table class="min-w-full table-auto border">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="border px-4 py-2">Nama</th>
                        <th class="border px-4 py-2">Deskripsi</th>
                        <th class="border px-4 py-2">Foto</th>
                        <th class="border px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in kegiatan.data" :key="item.id">
                        <td class="border px-4 py-2">{{ item.nama }}</td>
                        <td class="border px-4 py-2">{{ item.deskripsi }}</td>
                        <td class="border px-4 py-2">
                            <Link :href="route('kegiatanimage.index', { kegiatanId: item.id })">
                            <button class="bg-blue-600 text-white px-4 py-2 rounded">Tambah Foto</button>
                            </Link>
                        </td>
                        <td class="border px-4 py-2 space-x-2">
                            <Link :href="route('kegiatan.edit', item.id)" class="text-yellow-600 hover:underline">
                            <i class="fa fa-edit"></i>
                            </Link>
                            <button @click="hapus(item.id)" class="text-red-600 hover:underline">
                                <i class="fa fa-trash"></i>
                            </button>
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

defineProps({ kegiatan: Object })

function hapus(id) {
    if (confirm('Apakah Anda yakin ingin menghapus kegiatan ini?')) {
        router.delete(route('kegiatan.destroy', id))
    }
}
</script>
