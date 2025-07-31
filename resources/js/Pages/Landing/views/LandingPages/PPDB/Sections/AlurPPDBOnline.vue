<template>
  <section class="my-10 px-4">
    <h4 class="text-center text-2xl font-bold text-gray-800 mb-10">Alur PPDB</h4>

    <!-- Flex row container -->
    <div class="flex flex-row flex-nowrap overflow-x-auto items-center gap-6 px-4 pb-4">
      <div
        v-for="(step, i) in steps"
        :key="i"
        class="flex flex-row items-center gap-4 flex-shrink-0"
      >
        <!-- Card -->
        <div class="min-w-[12rem] bg-white rounded-lg shadow-md text-center py-4 px-3">
          <i :class="['material-icons text-4xl mb-2', step.color]">{{ step.icon }}</i>
          <h6 class="font-semibold text-base mb-1">{{ step.title }}</h6>
          <p class="text-sm text-gray-600">{{ step.desc }}</p>
        </div>

        <!-- Arrow -->
        <div
          v-if="i < steps.length - 1"
          class="text-2xl text-gray-400"
        >
          →
        </div>
      </div>
    </div>
  </section>
</template>



<script setup>
const steps = [
  { icon: 'person_add', title: 'Daftar Online', desc: 'Membuat akun di portal', color: 'text-red-500' },
  { icon: 'badge', title: 'Lengkapi Data Diri', desc: 'Isi Formulir', color: 'text-gray-800' },
  { icon: 'credit_card', title: 'Lengkapi Persyaratan', desc: 'Upload Berkas', color: 'text-blue-500' },
  { icon: 'notifications', title: 'Verifikasi', desc: 'Validasi oleh panitia', color: 'text-pink-500' },
  { icon: 'record_voice_over', title: 'Wawancara', desc: 'Dijadwalkan oleh admin', color: 'text-purple-500' },
  { icon: 'campaign', title: 'Hasil Tes', desc: 'Pengumuman hasil seleksi', color: 'text-orange-400' },
  { icon: 'how_to_reg', title: 'Daftar Ulang', desc: 'Konfirmasi ulang data', color: 'text-green-600' },
  { icon: 'groups', title: 'Selesai', desc: 'Menjadi siswa aktif', color: 'text-emerald-500' },
]
</script>
