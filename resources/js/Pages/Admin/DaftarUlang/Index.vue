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
            <td class="p-2 border">{{ item.pendaftar?.nama ?? '-' }}</td>
            <td class="p-2 border">
              <a :href="`/storage/${item.file_surat}`" target="_blank" class="text-blue-600 underline">Lihat</a>
            </td>
            <td class="p-2 border space-x-2">
              <button v-if="item.status === 'dikirim'" @click="verifikasi(item.id)" :disabled="loadingId === item.id"
                class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 disabled:opacity-50">
                Verifikasi
              </button>

              <button v-else-if="item.status === 'diverifikasi' && item.pendaftar?.status_pendaftaran !== 'selesai'"
                @click="selesaikan(item.id)" :disabled="loadingId === item.id"
                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded disabled:opacity-50">
                Tandai Selesai
              </button>

              <span v-else-if="item.pendaftar?.status_pendaftaran === 'selesai'" class="text-green-700 font-semibold">
                Selesai
              </span>
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
import { ref } from 'vue'

const props = defineProps({
  daftarUlang: Array
})

const loadingId = ref(null)

function verifikasi(id) {
  if (confirm('Verifikasi surat ini?')) {
    loadingId.value = id
    router.post(route('admin.daftar-ulang.verifikasi', id), {}, {
      onFinish: () => loadingId.value = null
    })
  }
}

function selesaikan(id) {
  if (confirm('Yakin ingin menandai pendaftar ini sebagai selesai?')) {
    loadingId.value = id
    router.post(route('admin.daftar-ulang.selesai', id), {}, {
      onFinish: () => loadingId.value = null
    })
  }
}

</script>
