<template>
  <AdminLayout>
    <div>
      <!-- Flash Message -->
      <div v-if="flashSuccess" class="bg-blue-100 text-blue-800 p-4 rounded mb-4">
        {{ flashSuccess }}
      </div>

      <!-- Tabel Siswa -->
      <div class="bg-white rounded shadow p-4">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold">Data Siswa</h3>
          <Link :href="route('siswa.create')">
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
              <i class="fa fa-plus mr-2"></i> Tambah
            </button>
          </Link>
        </div>

        <table class="min-w-full table-auto border">
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
            <tr v-for="data in siswa.data" :key="data.id" class="hover:bg-gray-50">
              <td class="px-4 py-2 border">{{ data.kelas }}</td>
              <td class="px-4 py-2 border">{{ data.jml_siswa }}</td>
              <td class="px-4 py-2 border">{{ data.laki_laki }}</td>
              <td class="px-4 py-2 border">{{ data.perempuan }}</td>
              <td class="px-4 py-2 border">{{ data.pegawai?.nama ?? '-' }}</td>
              <td class="px-4 py-2 border">
                <div class="flex gap-2">
                  <Link :href="route('siswa.edit', data.id)" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded">
                    <i class="fa fa-edit"></i>
                  </Link>
                  <button @click="hapus(data.id)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
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
  siswa: Object,
})

const flashSuccess = computed(() => usePage().props.value?.flash?.success)

function hapus(id) {
  if (confirm('Apakah Anda Yakin?')) {
    router.delete(route('siswa.destroy', id))
  }
}
</script>
