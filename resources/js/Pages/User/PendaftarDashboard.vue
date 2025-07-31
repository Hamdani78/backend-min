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
            ]"></div>
            <span class="ml-2 text-sm" :class="{ 'text-gray-600': currentStepIndex < index }">{{ step.label }}</span>
          </div>
          <div v-if="index < steps.length - 1" class="w-6 h-px bg-gray-300"></div>
        </template>
      </div>

      <!-- Ringkasan Data -->
      <div v-if="pendaftar" class="bg-white p-4 rounded shadow max-w-xl">
        <p><strong>Nama:</strong> {{ pendaftar.nama }}</p>
        <p><strong>NIK:</strong> {{ pendaftar.nik }}</p>
        <p><strong>Status:</strong> {{ statusLabel }}</p>
        <p><strong>Foto:</strong></p>
        <img v-if="pendaftar.foto" :src="`/storage/${pendaftar.foto}`" alt="Foto"
          class="w-28 h-36 object-cover rounded shadow mt-2" />
      </div>

      <!-- Berkas -->
      <div v-if="berkas" class="bg-white p-4 rounded shadow max-w-xl mt-4">
        <h2 class="font-semibold mb-2">Berkas yang Diupload</h2>
        <ul class="list-disc list-inside text-sm text-gray-700">
          <li v-if="berkas.ijazah_tk">Ijazah TK: <a :href="`/storage/${berkas.ijazah_tk}`" target="_blank"
              class="text-blue-600 underline">Lihat</a></li>
          <li v-if="berkas.akte_kelahiran">Akte Kelahiran: <a :href="`/storage/${berkas.akte_kelahiran}`"
              target="_blank" class="text-blue-600 underline">Lihat</a></li>
          <li v-if="berkas.kartu_keluarga">Kartu Keluarga: <a :href="`/storage/${berkas.kartu_keluarga}`"
              target="_blank" class="text-blue-600 underline">Lihat</a></li>
          <li v-if="berkas.kip">KIP: <a :href="`/storage/${berkas.kip}`" target="_blank"
              class="text-blue-600 underline">Lihat</a></li>
        </ul>
      </div>

      <!-- Surat Pernyataan -->
      <div v-if="pendaftar?.daftar_ulang?.file_surat" class="mt-4">
        <h3 class="font-semibold">Surat Pernyataan</h3>
        <a :href="`/storage/${pendaftar.daftar_ulang.file_surat}`" target="_blank"
          class="text-blue-600 hover:underline">
          Lihat Surat Pernyataan (PDF)
        </a>
      </div>

      <!-- Aksi -->
      <div class="mt-6">
        <template v-if="!pendaftar">
          <div class="text-red-600">
            <p>Anda belum mengisi formulir pendaftaran.</p>
            <button @click="router.visit('/user/pendaftaran')"
              class="mt-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
              Isi Formulir
            </button>
          </div>
        </template>

        <template v-else-if="statusPendaftaran === 'formulir'">
          <button @click="router.visit('/user/upload-berkas')"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            Upload Berkas
          </button>
        </template>

        <template v-else-if="statusPendaftaran === 'berkas'">
          <p class="text-sm text-gray-600">Silakan tunggu proses verifikasi dari admin.</p>
        </template>

        <template v-else-if="statusPendaftaran === 'wawancara'">
          <p class="text-yellow-700">Silakan datang ke madrasah untuk mengikuti wawancara pada jadwal yang telah
            ditentukan.</p>
        </template>

        <template v-if="statusPendaftaran === 'pengumuman'">
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

          <div v-else class="text-gray-600 italic">
            Menunggu pengumuman hasil seleksi...
          </div>
        </template>


        <template v-else-if="statusPendaftaran === 'verifikasi'">
          <button @click="router.visit('/user/daftar-ulang')"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
            Daftar Ulang
          </button>
        </template>


        <template v-else-if="statusPendaftaran === 'selesai'">
          <p class="text-green-600 font-semibold text-lg"> Pendaftaran Anda telah selesai. Terima kasih!</p>
        </template>
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
const pendaftar = page.props.pendaftar
const berkas = page.props.berkas
const statusPendaftaran = page.props.statusPendaftaran
const statusLulus = page.props.statusLulus
const nilaiSpk = page.props.nilaiSpk


const steps = [
  { name: 'formulir', label: 'Data Diri' },
  { name: 'berkas', label: 'Upload Berkas' },
  { name: 'wawancara', label: 'Wawancara' },
  { name: 'pengumuman', label: 'Pengumuman' },
  { name: 'verifikasi', label: 'Daftar Ulang' },
  { name: 'selesai', label: 'Selesai' },
]

const currentStepIndex = computed(() => {
  return steps.findIndex(step => step.name === statusPendaftaran)
})

const statusLabel = computed(() => {
  switch (statusPendaftaran) {
    case 'formulir': return 'Formulir Terkirim'
    case 'berkas': return 'Menunggu Verifikasi Berkas'
    case 'wawancara': return 'Menunggu Wawancara'
    case 'pengumuman': return 'Menunggu Pengumuman'
    case 'verifikasi': return 'Menunggu Daftar Ulang'
    case 'selesai': return 'Pendaftaran Selesai'
    default: return 'Belum Mengisi Formulir'
  }
})
</script>
