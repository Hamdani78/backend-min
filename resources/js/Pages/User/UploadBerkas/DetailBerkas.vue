<template>
  <UserLayout>
    <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
      <h2 class="text-xl font-bold mb-4">Detail Berkas Pendaftaran</h2>

      <div v-if="berkas" class="space-y-4">
        <table class="w-full border text-sm">
          <tbody>
            <tr>
              <td class="font-medium p-2 border w-1/3">Ijazah TK</td>
              <td class="p-2 border">
                <span v-if="berkas.ijazah_tk">
                  <a
                    :href="`/storage/${berkas.ijazah_tk}`"
                    target="_blank"
                    class="text-blue-600 underline flex items-center gap-1"
                  >
                    📄 Lihat File
                  </a>
                </span>
                <span v-else class="text-gray-400 italic">Tidak tersedia</span>
              </td>
            </tr>

            <tr>
              <td class="font-medium p-2 border">Akte Kelahiran</td>
              <td class="p-2 border">
                <span v-if="berkas.akte_kelahiran">
                  <a
                    :href="`/storage/${berkas.akte_kelahiran}`"
                    target="_blank"
                    class="text-blue-600 underline flex items-center gap-1"
                  >
                    📄 Lihat File
                  </a>
                </span>
                <span v-else class="text-gray-400 italic">Tidak tersedia</span>
              </td>
            </tr>

            <tr>
              <td class="font-medium p-2 border">Kartu Keluarga</td>
              <td class="p-2 border">
                <span v-if="berkas.kartu_keluarga">
                  <a
                    :href="`/storage/${berkas.kartu_keluarga}`"
                    target="_blank"
                    class="text-blue-600 underline flex items-center gap-1"
                  >
                    📄 Lihat File
                  </a>
                </span>
                <span v-else class="text-gray-400 italic">Tidak tersedia</span>
              </td>
            </tr>

            <tr>
              <td class="font-medium p-2 border">KIP (Opsional)</td>
              <td class="p-2 border">
                <span v-if="berkas.kip">
                  <a
                    :href="`/storage/${berkas.kip}`"
                    target="_blank"
                    class="text-blue-600 underline flex items-center gap-1"
                  >
                    📄 Lihat File
                  </a>
                </span>
                <span v-else class="text-gray-400 italic">Tidak tersedia</span>
              </td>
            </tr>
          </tbody>
        </table>

        <button
          @click="goToEditPage"
          class="mt-4 bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600"
        >
          Edit Berkas
        </button>
      </div>

      <div v-else class="text-red-600">
        <p>Berkas belum diunggah.</p>
      </div>
    </div>
  </UserLayout>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import UserLayout from '@/Pages/User/UserLayouts/UserLayout.vue'

const props = defineProps({
  berkas: Object
})
console.log('Berkas:', props.berkas)
function goToEditPage() {
  if (confirm('Apakah Anda yakin ingin mengedit berkas ini?')) {
    router.visit(route('user.berkas.edit'))
  }
}
</script>
