<!-- resources/js/Pages/User/BerkasDetail.vue -->
<template>
  <UserLayout>
    <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
      <h2 class="text-xl font-bold mb-4">Detail Berkas Pendaftaran</h2>

      <div v-if="berkas" class="space-y-4">
        <div class="overflow-x-auto rounded border">
          <table class="w-full text-sm">
            <tbody>
              <tr v-for="row in rows" :key="row.key" class="border-b last:border-b-0">
                <td class="font-medium px-4 py-3 w-1/3">{{ row.label }}</td>
                <td class="px-4 py-3">
                  <template v-if="row.path">
                    <div class="flex items-center gap-3 flex-wrap">
                      <span class="inline-flex items-center gap-2 text-gray-700">
                        <span v-html="icons.doc"></span>
                        <span class="truncate max-w-[320px]">{{ fileName(row.path) }}</span>
                        <span class="text-gray-400">· {{ ext(row.path).toUpperCase() }}</span>
                      </span>
                      <a class="text-blue-600 hover:text-blue-700 underline" :href="url(row.path)" target="_blank" rel="noopener">Lihat File</a>
                      <a class="text-gray-700 hover:text-black underline" :href="url(row.path)" :download="fileName(row.path)">Unduh</a>
                    </div>
                  </template>
                  <span v-else class="text-gray-400 italic">Tidak tersedia</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Banner kunci setelah verifikasi -->
        <div v-if="isLocked" class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
          Berkas Anda telah diverifikasi oleh admin sehingga tidak bisa diubah.
        </div>

        <!-- TOMBOL EDIT hanya jika boleh -->
        <div class="mt-4" v-if="!isLocked">
          <Link :href="route('user.berkas.edit')" as="button" type="button"
                class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded">
            Edit Berkas
          </Link>
        </div>
      </div>

      <div v-else class="text-red-600">
        <p>Berkas belum diunggah.</p>
      </div>
    </div>
  </UserLayout>
</template>

<script setup>
import UserLayout from '@/Pages/User/UserLayouts/UserLayout.vue'
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  berkas: { type: Object, default: null },
  // kirim dari controller
  statusPendaftaran: { type: String, default: null },
  // kalau mau, bisa langsung kirim boolean canEditBerkas dari server
  canEditBerkas: { type: Boolean, default: null },
})

const rows = computed(()=>[
  { key:'ijazah_tk',      label:'Ijazah TK',      path: props.berkas?.ijazah_tk || null },
  { key:'akte_kelahiran', label:'Akte Kelahiran', path: props.berkas?.akte_kelahiran || null },
  { key:'kartu_keluarga', label:'Kartu Keluarga', path: props.berkas?.kartu_keluarga || null },
  { key:'kip',            label:'KIP (Opsional)', path: props.berkas?.kip ?? null },
])

// aturan kunci: setelah diverifikasi & seterusnya tidak boleh edit
const isLocked = computed(() => {
  if (props.canEditBerkas !== null) return !props.canEditBerkas
  const s = props.statusPendaftaran
  return ['berkas_terverifikasi', 'wawancara', 'pengumuman', 'verifikasi', 'selesai'].includes(s)
})

const url = (p)=> `/storage/${p}`
const fileName = (p)=> p?.split('/').pop() || ''
const ext = (p)=> {
  const n = fileName(p); return n.includes('.') ? n.split('.').pop() : ''
}

const icons = {
  doc: `
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
        d="M9 12h6m-6 4h6M9 8h3m-5 12h8a2 2 0 0 0 2-2V7.5L13.5 3H8a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2z" />
    </svg>`
}
</script>
