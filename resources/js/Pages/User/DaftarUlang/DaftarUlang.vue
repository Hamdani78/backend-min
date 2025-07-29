<template>
  <UserLayout>
    <div class="bg-white p-6 rounded shadow max-w-3xl mx-auto leading-relaxed">
      <h2 class="text-center font-bold text-lg uppercase mb-6">
        Surat Pernyataan Orang Tua / Wali <br />
        Calon Murid MIN 1 Rokan Hulu
      </h2>

      <form @submit.prevent="submit" class="space-y-4">
        <div class="grid grid-cols-4 gap-4 items-center">
          <label class="col-span-1 text-left">Nama</label>
          <input v-model="form.nama" class="col-span-3 border px-2 py-1 rounded" required />
        </div>

        <div class="grid grid-cols-4 gap-4 items-center">
          <label class="col-span-1 text-left">Pekerjaan</label>
          <input v-model="form.pekerjaan" class="col-span-3 border px-2 py-1 rounded" required />
        </div>

        <div class="grid grid-cols-4 gap-4 items-center">
          <label class="col-span-1 text-left">Agama</label>
          <input v-model="form.agama" class="col-span-3 border px-2 py-1 rounded" required />
        </div>

        <div class="grid grid-cols-4 gap-4 items-center">
          <label class="col-span-1 text-left">Orang Tua / Wali dari</label>
          <input v-model="form.anak" class="col-span-3 border px-2 py-1 rounded" required />
        </div>

        <div class="grid grid-cols-4 gap-4 items-center">
          <label class="col-span-1 text-left">Hubungan dengan calon murid</label>
          <input v-model="form.hubungan" class="col-span-3 border px-2 py-1 rounded" required />
        </div>

        <div class="grid grid-cols-4 gap-4 items-start">
          <label class="col-span-1 text-left pt-2">Alamat</label>
          <textarea v-model="form.alamat" rows="2" class="col-span-3 border px-2 py-1 rounded" required></textarea>
        </div>

        <p class="mt-6">Dengan sungguh-sungguh dan penuh kesadaran, menyatakan:</p>

        <ul class="list-disc list-inside ml-4 space-y-1">
          <li>Bersedia membimbing dan mengawasi anak kami tersebut untuk memenuhi peraturan madrasah.</li>
          <li>Anak kami akan mengikuti Pendidikan Agama Islam di madrasah.</li>
          <li>Menerima sanksi:
            <ul class="list-[lower-alpha] ml-6">
              <li>Anak kami tidak diperkenankan mengikuti pelajaran untuk sementara waktu jika melanggar tata tertib.</li>
              <li>Anak kami dikeluarkan dari madrasah jika melanggar ketentuan berat dari madrasah.</li>
            </ul>
          </li>
        </ul>

        <div class="text-right mt-6">
          <p>Pasir Pengaraian, {{ tanggalHariIni }}</p>
          <p class="mt-10 font-semibold">Yang Membuat Pernyataan<br />Orang Tua / Wali</p>
        </div>

        <div class="mt-6 text-center">
          <button class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Kirim Surat</button>
        </div>
      </form>
    </div>
  </UserLayout>
</template>

<script setup>
import UserLayout from '@/Pages/User/UserLayouts/UserLayout.vue'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const form = ref({
  nama: '',
  pekerjaan: '',
  agama: '',
  anak: '',
  hubungan: '',
  alamat: '',
  tanggal: '',
})

const getTodayIndo = () => {
  const bulan = [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
  ]

  const now = new Date()
  const tanggal = now.getDate()
  const bulanStr = bulan[now.getMonth()]
  const tahun = now.getFullYear()

  return `${tanggal} ${bulanStr} ${tahun}`
}

const tanggalHariIni = ref(getTodayIndo())

function submit() {
  form.value.tanggal = tanggalHariIni.value
  router.post('/user/daftar-ulang', form.value)
}

</script>
