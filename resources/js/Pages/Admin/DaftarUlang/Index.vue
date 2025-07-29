<template>
  <AdminLayout>
    <div class="p-6 bg-white shadow rounded">
      <h2 class="text-xl font-bold mb-4">Surat Pernyataan Daftar Ulang</h2>

      <table class="table-auto w-full text-sm">
        <thead class="bg-gray-100">
          <tr>
            <th class="p-2 border">Nama</th>
            <th class="p-2 border">Surat Pernyataan</th>
            <th class="p-2 border">Status</th>
            <th class="p-2 border">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in daftarUlang" :key="item.id" class="border">
            <td class="p-2">{{ item.pendaftar?.nama ?? '-' }}</td>
            <td class="p-2">
              <a :href="`/storage/${item.file_surat}`" target="_blank" class="text-blue-600 underline">Lihat</a>
            </td>
            <td class="p-2">
              <span v-if="item.status === 'dikirim'" class="text-orange-500">Menunggu</span>
              <span v-else class="text-green-600">Diverifikasi</span>
            </td>
            <td class="p-2">
              <button
                v-if="item.status === 'dikirim'"
                @click="verifikasi(item.id)"
                class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
                Verifikasi
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
import { router } from '@inertiajs/vue3'
const props = defineProps({ daftarUlang: Array })

function verifikasi(id) {
  if (confirm('Verifikasi surat ini?')) {
    router.post(route('admin.daftar-ulang.verifikasi', id))
  }
}
</script>
