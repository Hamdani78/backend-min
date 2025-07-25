<template>
  <AdminLayout>
    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
      <h2 class="text-xl font-bold mb-4">Edit Pendaftar</h2>

      <form @submit.prevent="submit" enctype="multipart/form-data">
        <!-- Data Dasar -->
        <div class="mb-4">
          <label class="block font-medium">Nama</label>
          <input v-model="form.nama" type="text" class="form-input w-full mt-1 border rounded p-2" />
        </div>

        <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block font-medium">Tempat Lahir</label>
            <input v-model="form.tempat_lahir" type="text" class="form-input w-full mt-1 border rounded p-2" />
          </div>
          <div>
            <label class="block font-medium">Tanggal Lahir</label>
            <input v-model="form.tanggal_lahir" type="date" class="form-input w-full mt-1 border rounded p-2" />
          </div>
        </div>

        <!-- Jenis Kelamin -->
        <div class="mb-4">
          <label class="block font-medium">Jenis Kelamin</label>
          <select v-model="form.jenis_kelamin" class="form-select w-full mt-1 border rounded p-2">
            <option value="">Pilih</option>
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
          </select>
        </div>

        <!-- Foto -->
        <div class="mb-4">
          <label class="block font-medium">Foto 3x4</label>
          <input type="file" @change="handleFoto" accept="image/*" class="mt-2" />
          <div class="mt-2 flex gap-4 items-center">
            <img v-if="previewFoto" :src="previewFoto" alt="Preview Baru" class="w-32 rounded border" />
            <img v-else-if="form.foto" :src="`/storage/${form.foto}`" alt="Foto Lama" class="w-32 rounded border" />
            <span v-else class="text-gray-500 italic">Belum ada foto</span>
          </div>
        </div>

        <!-- Orang Tua -->
        <div class="mt-8">
          <h4 class="text-lg font-semibold mb-2">Data Orang Tua</h4>

          <div v-for="(ortu, index) in form.orang_tuas" :key="index" class="mb-6 p-4 border rounded bg-gray-50">
            <h5 class="font-bold mb-2">{{ ortu.tipe }}</h5>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div><label class="block">Nama</label><input v-model="ortu.nama" type="text" class="form-input w-full" /></div>
              <div><label class="block">Status</label>
                <select v-model="ortu.status" class="form-select w-full">
                  <option>Masih Hidup</option>
                  <option>Sudah Meninggal</option>
                </select>
              </div>
              <div><label class="block">NIK</label><input v-model="ortu.nik" type="text" class="form-input w-full" /></div>
              <div><label class="block">Tempat Lahir</label><input v-model="ortu.tempat_lahir" type="text" class="form-input w-full" /></div>
              <div><label class="block">Tanggal Lahir</label><input v-model="ortu.tanggal_lahir" type="date" class="form-input w-full" /></div>
              <div><label class="block">Pendidikan</label><input v-model="ortu.pendidikan" type="text" class="form-input w-full" /></div>
              <div><label class="block">Pekerjaan</label><input v-model="ortu.pekerjaan" type="text" class="form-input w-full" /></div>
              <div><label class="block">Penghasilan</label><input v-model="ortu.penghasilan" type="text" class="form-input w-full" /></div>
              <div><label class="block">No HP</label><input v-model="ortu.no_hp" type="text" class="form-input w-full" /></div>
            </div>
          </div>
        </div>

        <!-- Wali -->
        <div class="mt-8">
          <h4 class="text-lg font-semibold mb-2">Data Wali</h4>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block">Nama Wali</label>
              <input v-model="form.wali.nama" type="text" class="form-input w-full" />
            </div>
            <div>
              <label class="block">Hubungan Keluarga</label>
              <input v-model="form.wali.hubungan_keluarga" type="text" class="form-input w-full" />
            </div>
          </div>
        </div>

        <!-- Tombol -->
        <div class="mt-6">
          <button :disabled="form.processing" type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
  pendaftar: Object,
})

const form = useForm({
  nama: props.pendaftar.nama || '',
  tempat_lahir: props.pendaftar.tempat_lahir || '',
  tanggal_lahir: props.pendaftar.tanggal_lahir || '',
  jenis_kelamin: props.pendaftar.jenis_kelamin || '',
  foto: props.pendaftar.foto || '',

  orang_tuas: props.pendaftar.orang_tuas || [
    { tipe: 'Ayah', nama: '', status: '', nik: '', tempat_lahir: '', tanggal_lahir: '', pendidikan: '', pekerjaan: '', penghasilan: '', no_hp: '' },
    { tipe: 'Ibu', nama: '', status: '', nik: '', tempat_lahir: '', tanggal_lahir: '', pendidikan: '', pekerjaan: '', penghasilan: '', no_hp: '' },
  ],

  wali: props.pendaftar.wali || {
    nama: '',
    hubungan_keluarga: '',
  },
})

const previewFoto = ref(null)

function handleFoto(e) {
  const file = e.target.files[0]
  form.foto = file

  if (file) {
    const reader = new FileReader()
    reader.onload = (e) => (previewFoto.value = e.target.result)
    reader.readAsDataURL(file)
  }
}

function submit() {
  form.transform((data) => ({
    ...data,
    _method: 'put',
  })).post(route('pendaftar.update', props.pendaftar.id), {
    forceFormData: true,
    preserveScroll: true,
    onError: (errors) => {
      console.error(errors)
    },
  })
}

</script>
