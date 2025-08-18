<template>
  <UserLayout>
    <div class="p-4">
      <h1 class="text-2xl font-bold mb-4">Dashboard Pendaftar</h1>
      <p class="mb-6">Selamat datang, {{ user.name }}</p>

      <!-- Stepper -->
      <div class="flex items-center space-x-4 mb-6">
        <template v-for="(step, index) in steps" :key="step.name">
          <div class="flex items-center">
            <div :class="[
              'w-5 h-5 rounded-full border-2',
              currentStepIndex >= index ? 'bg-green-500 border-green-500' : 'bg-gray-200 border-gray-300'
            ]" />
            <span class="ml-2 text-sm" :class="{ 'text-gray-600': currentStepIndex < index }">
              {{ step.label }}
            </span>
          </div>
          <div v-if="index < steps.length - 1" class="w-6 h-px bg-gray-300"></div>
        </template>
      </div>

      <!-- Belum isi formulir -->
      <div v-if="!pendaftar" class="text-red-600">
        <p>Anda belum mengisi formulir pendaftaran.</p>
        <button @click="router.visit('/user/pendaftaran')"
          class="mt-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
          Isi Formulir
        </button>
      </div>

      <!-- GRID: kiri = data diri, kanan = pesan -->
      <div v-else class="max-w-6xl">
        <div class="grid md:grid-cols-2 gap-4 items-start">
          <!-- KIRI: Card Data Diri -->
          <div class="bg-white p-5 rounded-xl shadow">
            <h2 class="font-semibold text-lg mb-3">Data Diri</h2>

            <div class="space-y-3">
              <!-- Nama -->
              <div class="flex">
                <div class="w-40 shrink-0 text-gray-500 after:content-[':'] after:ml-1">
                  <strong>Nama</strong>
                </div>
                <div class="font-medium text-gray-900">
                  {{ pendaftar?.nama ?? '-' }}
                </div>
              </div>

              <!-- NIK -->
              <div class="flex">
                <div class="w-40 shrink-0 text-gray-500 after:content-[':'] after:ml-1">
                  <strong>NIK</strong>
                </div>
                <div class="font-mono text-gray-900">
                  {{ pendaftar?.nik ?? '-' }}
                </div>
              </div>

              <!-- Status -->
              <div class="flex items-center">
                <div class="w-40 shrink-0 text-gray-500 after:content-[':'] after:ml-1">
                  <strong>Status</strong>
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium" :class="{
                  'bg-yellow-100 text-yellow-800': statusNow === 'berkas',
                  'bg-green-100 text-green-800': statusNow === 'berkas_terverifikasi',
                  'bg-purple-100 text-purple-800': statusNow === 'wawancara',
                  'bg-blue-100 text-blue-800': statusNow === 'pengumuman'
                }">
                  {{ statusLabel }}
                </span>
              </div>

              <!-- Foto -->
              <div class="flex items-start">
                <div class="w-40 shrink-0 text-gray-500 after:content-[':'] after:ml-1">
                  <strong>Foto</strong>
                </div>
                <template v-if="pendaftar?.foto">
                  <img :src="`/storage/${pendaftar.foto}`" alt="Foto" class="w-28 h-36 object-cover rounded shadow" />
                </template>
                <span v-else class="text-gray-400 italic">Tidak ada foto</span>
              </div>
            </div>
          </div>

          <!-- KANAN: Pesan sesuai status -->
          <aside class="space-y-3">
            <div v-if="statusNow === 'formulir'">
              <button @click="router.visit('/user/upload-berkas')"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                Upload Berkas
              </button>
            </div>

            <div v-else-if="statusNow === 'berkas'" class="bg-gray-50 border border-gray-200 text-gray-700 p-3 rounded">
              Silakan tunggu proses verifikasi berkas oleh admin.
            </div>

            <div v-else-if="statusNow === 'berkas_terverifikasi'"
              class="bg-green-50 border border-green-200 text-green-800 p-3 rounded">
              Berkas Anda telah diverifikasi. Form dan berkas terkunci. Silakan menunggu jadwal wawancara dari admin.
            </div>

            <div v-else-if="statusNow === 'wawancara'"
              class="bg-yellow-50 border border-yellow-200 text-yellow-800 p-3 rounded">
              <p class="font-medium">Silakan datang untuk wawancara pada:</p>
              <div class="mt-1">
                <strong>{{ fmt(pendaftar?.wawancara_at) }}</strong>
                <span v-if="pendaftar?.wawancara_tempat"> · {{ pendaftar.wawancara_tempat }}</span>
              </div>
              <div v-if="pendaftar?.wawancara_catatan" class="text-sm mt-1">
                Catatan: {{ pendaftar.wawancara_catatan }}
              </div>
            </div>

            <div v-else-if="statusNow === 'pengumuman'">
              <div v-if="statusLulus === 'lulus'" class="bg-green-100 text-green-800 p-4 rounded">
                Selamat! Anda dinyatakan <strong>LULUS</strong>.
                <div class="mt-2">
                  <button @click="router.visit('/user/daftar-ulang')"
                    class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                    Lanjut Daftar Ulang
                  </button>
                </div>
              </div>
              <div v-else-if="statusLulus === 'tidak_lulus'" class="bg-red-100 text-red-700 p-4 rounded">
                Maaf, Anda belum lulus seleksi tahun ini.
              </div>
              <div v-else class="bg-blue-50 border border-blue-200 text-blue-800 p-3 rounded">
                Menunggu pengumuman hasil seleksi...
              </div>
            </div>

            <div v-else-if="statusNow === 'verifikasi'">
              <button @click="router.visit('/user/daftar-ulang')"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
                Daftar Ulang
              </button>
            </div>

            <div v-else-if="statusNow === 'selesai'" class="text-green-600 font-semibold text-lg">
              Pendaftaran Anda telah selesai. Terima kasih!
            </div>

            <div v-else class="text-gray-600 italic">Status belum tersedia.</div>
          </aside>
        </div>

        <!-- BERKAS (rapi & responsif) -->
        <div v-if="berkas" class="bg-white p-5 rounded-xl shadow max-w-3xl mt-4">
          <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold text-lg">Berkas yang Diupload</h2>
            <span class="text-xs px-2 py-0.5 rounded border"
              :class="isComplete ? 'bg-green-50 border-green-200 text-green-700' : 'bg-yellow-50 border-yellow-200 text-yellow-700'">
              {{ isComplete ? 'Lengkap' : 'Belum Lengkap' }}
            </span>
          </div>

          <div class="grid sm:grid-cols-2 gap-3">
            <div v-for="d in docList" :key="d.key" class="border rounded-lg p-3 flex items-start gap-3">
              <i class="fa-regular fa-file text-xl mt-1 text-gray-400"></i>
              <div class="flex-1">
                <div class="text-sm text-gray-500">{{ d.label }}</div>
                <div class="mt-1">
                  <template v-if="d.path">
                    <a :href="`/storage/${d.path}`" target="_blank" rel="noopener noreferrer"
                      class="inline-flex items-center text-blue-600 hover:underline text-sm">
                      Lihat
                      <i class="fa-solid fa-arrow-up-right-from-square ml-1 text-xs"></i>
                    </a>
                  </template>
                  <span v-else class="text-gray-400 italic text-sm">Belum ada</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- SURAT PERNYATAAN (jika ada) -->
        <div v-if="pendaftar?.daftar_ulang?.file_surat" class="mt-4">
          <h3 class="font-semibold">Surat Pernyataan</h3>
          <a :href="`/storage/${pendaftar.daftar_ulang.file_surat}`" target="_blank" rel="noopener noreferrer"
            class="text-blue-600 hover:underline">
            Lihat Surat Pernyataan (PDF)
          </a>
        </div>
      </div>
    </div>
  </UserLayout>
</template>

<script setup>
import { usePage, router } from '@inertiajs/vue3'
import UserLayout from './UserLayouts/UserLayout.vue'
import { computed } from 'vue'

const page = usePage()
const user = page.props.auth.user

// make reactive to Inertia prop updates
const pendaftar = computed(() => page.props.pendaftar)
const berkas = computed(() => page.props.berkas)

const statusNow = computed(() => page.props.statusPendaftaran ?? pendaftar.value?.status_pendaftaran ?? null)
const statusLulus = computed(() =>
  page.props.statusLulus ?? pendaftar.value?.status_lulus ?? null
)

const steps = [
  { name: 'formulir', label: 'Data Diri' },
  { name: 'berkas', label: 'Upload Berkas' },
  { name: 'berkas_terverifikasi', label: 'Berkas Terverifikasi' },
  { name: 'wawancara', label: 'Wawancara' },
  { name: 'pengumuman', label: 'Pengumuman' },
  { name: 'verifikasi', label: 'Daftar Ulang' },
  { name: 'selesai', label: 'Selesai' },
]

const currentStepIndex = computed(() => {
  const s = statusNow.value ?? 'formulir'
  const idx = steps.findIndex(step => step.name === s)
  return idx >= 0 ? idx : 0
})

const statusLabel = computed(() => {
  switch (statusNow.value) {
    case 'formulir':             return 'Formulir Terkirim'
    case 'berkas':               return 'Menunggu Verifikasi Berkas'
    case 'berkas_terverifikasi': return 'Berkas Terverifikasi (Form Terkunci)'
    case 'wawancara':            return 'Menunggu Wawancara'
    case 'pengumuman':
      if (statusLulus.value === 'lulus') return 'Lulus'
      if (statusLulus.value === 'tidak_lulus') return 'Tidak Lulus'
      return 'Menunggu Pengumuman'
    case 'verifikasi':           return 'Menunggu Daftar Ulang'
    case 'selesai':              return 'Pendaftaran Selesai'
    default:                     return pendaftar.value ? 'Formulir Terkirim' : 'Belum Mengisi Formulir'
  }
})

function fmt(iso) {
  if (!iso) return '-'
  try {
    return new Date(iso).toLocaleString('id-ID', { dateStyle: 'full', timeStyle: 'short' })
  } catch {
    return iso
  }
}

const docList = computed(() => {
  const b = berkas.value || {}
  return [
    { key: 'ijazah_tk', label: 'Ijazah TK', path: b.ijazah_tk },
    { key: 'akte_kelahiran', label: 'Akte Kelahiran', path: b.akte_kelahiran },
    { key: 'kartu_keluarga', label: 'Kartu Keluarga', path: b.kartu_keluarga },
    { key: 'kip', label: 'KIP', path: b.kip },
  ]
})

const isComplete = computed(() => docList.value.slice(0, 3).every(d => !!d.path))
</script>
