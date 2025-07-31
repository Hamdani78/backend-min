<template>
  <AdminLayout>
    <div>
      <!-- Flash Message -->
      <div v-if="flashSuccess" class="bg-blue-100 text-blue-800 p-4 rounded mb-4">
        {{ flashSuccess }}
      </div>

      <!-- Tabel Berkas -->
      <div class="bg-white rounded shadow p-4">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold">Data Berkas Pendaftaran</h3>
          <Link :href="route('berkas-pendaftaran.create')">
          <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            <i class="fa fa-plus mr-2"></i> Upload
          </button>
          </Link>
        </div>

        <table class="min-w-full table-auto border text-sm">
          <thead>
            <tr class="bg-gray-100 text-left">
              <th class="px-4 py-2 border">Nama Pendaftar</th>
              <th class="px-4 py-2 border">Ijazah TK</th>
              <th class="px-4 py-2 border">Akte</th>
              <th class="px-4 py-2 border">Kartu Keluarga</th>
              <th class="px-4 py-2 border">KIP</th>
              <th class="px-4 py-2 border">Status</th>
              <th class="px-4 py-2 border">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in berkas" :key="item.id" class="hover:bg-gray-50">
              <td class="px-4 py-2 border">{{ item.pendaftar?.nama ?? '-' }}</td>

              <td class="px-4 py-2 border">
                <a v-if="item.ijazah_tk" :href="`/storage/${item.ijazah_tk}`" target="_blank"
                  class="text-blue-600 hover:underline">
                  Lihat
                </a>
                <span v-else class="text-gray-400 italic">-</span>
              </td>

              <td class="px-4 py-2 border">
                <a v-if="item.akte_kelahiran" :href="`/storage/${item.akte_kelahiran}`" target="_blank"
                  class="text-blue-600 hover:underline">
                  Lihat
                </a>
                <span v-else class="text-gray-400 italic">-</span>
              </td>

              <td class="px-4 py-2 border">
                <a v-if="item.kartu_keluarga" :href="`/storage/${item.kartu_keluarga}`" target="_blank"
                  class="text-blue-600 hover:underline">
                  Lihat
                </a>
                <span v-else class="text-gray-400 italic">-</span>
              </td>

              <td class="px-4 py-2 border">
                <a v-if="item.kip" :href="`/storage/${item.kip}`" target="_blank" class="text-blue-600 hover:underline">
                  Lihat
                </a>
                <span v-else class="text-gray-400 italic">-</span>
              </td>
              <td class="px-4 py-2 border">
                <div v-if="item.pendaftar?.status_pendaftaran === 'berkas'">
                  <button @click="ubahStatus(item.pendaftar.id, 'wawancara')"
                    class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded">
                    Set Wawancara
                  </button>
                </div>
                <div v-else-if="item.pendaftar?.status_pendaftaran === 'wawancara'">
                  <button @click="ubahStatus(item.pendaftar.id, 'pengumuman')"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
                    Tandai Selesai Wawancara
                  </button>
                </div>
                <div v-else-if="item.pendaftar?.status_pendaftaran === 'pengumuman'">
                  <span class="text-yellow-600 font-semibold">Menunggu Pengumuman</span>
                </div>
                <div v-else>
                  <span class="text-gray-400 italic">-</span>
                </div>
              </td>

              <td class="px-4 py-2 border">
                <div class="flex gap-2 flex-wrap">
                  <Link :href="route('berkas-pendaftaran.edit', item.id)"
                    class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded">
                  <i class="fa fa-edit"></i>
                  </Link>
                  <button @click="hapus(item.id)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
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
import { computed } from 'vue'

const props = defineProps({
  berkas: Array
})

const flashSuccess = computed(() => usePage().props.value?.flash?.success)
function konfirmasiVerifikasi(pendaftarId) {
  if (confirm('Yakin ingin memverifikasi berkas ini?')) {
    router.post(route('admin.berkas.verifikasi', pendaftarId), {}, {
      onSuccess: () => {
      }
    })
  }
}
function ubahStatus(id, status) {
  if (confirm(`Yakin ingin mengubah status ke ${status}?`)) {
    router.post(route('admin.pendaftar.status.update', id), {
      status: status
    })
  }
}

function setWawancara(id) {
  if (confirm('Set status ke Wawancara?')) {
    router.post(route('admin.berkas.wawancara', id));
  }
}

function verifikasiSetelahWawancara(id) {
  if (confirm('Tandai sudah selesai wawancara dan lanjut ke verifikasi?')) {
    router.post(route('admin.berkas.selesai-wawancara', id));
  }
}

function hapus(id) {
  if (confirm('Yakin ingin menghapus berkas ini?')) {
    router.delete(route('berkas-pendaftaran.destroy', id))
  }
}
</script>
