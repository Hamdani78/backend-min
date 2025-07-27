<template>
  <AdminLayout>
    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
      <h2 class="text-xl font-bold mb-4">Tambah Pendaftar</h2>

      <form @submit.prevent="submit" enctype="multipart/form-data">
        <!-- 🔹 User Selector -->
        <div class="mb-4">
          <label class="block font-medium">User</label>
          <select v-model="form.user_id" class="form-select w-full mt-1 border rounded p-2">
            <option value="">-- Pilih User --</option>
            <option v-for="user in users" :key="user.id" :value="user.id">
              {{ user.name }}
            </option>
          </select>
          <div v-if="form.errors.user_id" class="text-red-500 text-sm mt-1">{{ form.errors.user_id }}</div>
        </div>

        <!-- 🔹 Nama + Foto (sejajar) -->
        <div class="mb-4 flex flex-col md:flex-row md:items-start gap-4">
          <div class="flex-1">
            <label class="block font-medium">Nama</label>
            <input v-model="form.nama" type="text" class="form-input w-full mt-1 border rounded p-2" />
            <div v-if="form.errors.nama" class="text-red-500 text-sm mt-1">{{ form.errors.nama }}</div>
          </div>

          <div class="w-full md:w-1/3">
            <label class="block font-medium">Foto 3x4</label>
            <input type="file" @change="handleFoto" accept="image/*" class="mt-2" />
            <div v-if="previewFoto" class="mt-2">
              <img :src="previewFoto" alt="Preview Foto" class="w-full max-w-[120px] rounded border shadow" />
            </div>
            <div v-if="form.errors.foto" class="text-red-500 text-sm mt-1">{{ form.errors.foto }}</div>
          </div>
        </div>

        <!-- 🔹 Data Dasar -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <div>
            <label class="block font-medium">Tempat Lahir</label>
            <input v-model="form.tempat_lahir" type="text" class="form-input w-full mt-1 border rounded p-2" />
          </div>
          <div>
            <label class="block font-medium">Tanggal Lahir</label>
            <input v-model="form.tanggal_lahir" type="date" class="form-input w-full mt-1 border rounded p-2" />
          </div>
        </div>

        <div class="mb-4">
          <label class="block font-medium">Jenis Kelamin</label>
          <select v-model="form.jenis_kelamin" class="form-select w-full mt-1 border rounded p-2">
            <option value="">Pilih</option>
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
          </select>
        </div>

        <div class="mb-4">
          <label class="block font-medium">Agama</label>
          <input v-model="form.agama" type="text" class="form-input w-full mt-1 border rounded p-2" />
        </div>
        
        <div class="mb-4">
          <label class="block font-medium">Hobi</label>
          <input v-model="form.hobi" type="text" class="form-input w-full mt-1 border rounded p-2" />
        </div>

        <div class="mb-4">
          <label class="block font-medium">Bahasa Sehari-hari</label>
          <input v-model="form.bahasa" type="text" class="form-input w-full mt-1 border rounded p-2" />
        </div>

        <div class="mb-4">
          <label class="block font-medium">Cita-cita</label>
          <input v-model="form.cita_cita" type="text" class="form-input w-full mt-1 border rounded p-2" />
        </div>

        <div class="mb-4">
          <label class="block font-medium">Alamat Lengkap</label>
          <textarea v-model="form.alamat" rows="3" class="form-textarea w-full mt-1 border rounded p-2"></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <div>
            <label class="block font-medium">Provinsi</label>
            <input v-model="form.provinsi" type="text" class="form-input w-full mt-1 border rounded p-2" />
          </div>
          <div>
            <label class="block font-medium">Kabupaten/Kota</label>
            <input v-model="form.kabupaten" type="text" class="form-input w-full mt-1 border rounded p-2" />
          </div>
          <div>
            <label class="block font-medium">Kecamatan</label>
            <input v-model="form.kecamatan" type="text" class="form-input w-full mt-1 border rounded p-2" />
          </div>
          <div>
            <label class="block font-medium">Kelurahan/Desa</label>
            <input v-model="form.kelurahan" type="text" class="form-input w-full mt-1 border rounded p-2" />
          </div>
        </div>

        <div class="mb-4">
          <label class="block font-medium">No HP</label>
          <input v-model="form.no_hp" type="text" class="form-input w-full mt-1 border rounded p-2" />
        </div>
        <div class="mb-4">
          <label class="block font-medium">Nomor KK</label>
          <input v-model="form.no_kk" type="text" class="form-input w-full mt-1 border rounded p-2" />
        </div>

        <div class="mb-4">
          <label class="block font-medium">NIK Anak</label>
          <input v-model="form.nik" type="text" class="form-input w-full mt-1 border rounded p-2" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <div>
            <label class="block font-medium">Anak Ke</label>
            <input v-model="form.anak_ke" type="number" class="form-input w-full mt-1 border rounded p-2" />
          </div>
          <div>
            <label class="block font-medium">Jumlah Saudara</label>
            <input v-model="form.jumlah_saudara" type="number" class="form-input w-full mt-1 border rounded p-2" />
          </div>
        </div>

        <!-- Berat, Tinggi, Jasmani -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
          <div>
            <label class="block font-medium">Berat Badan (kg)</label>
            <input v-model="form.berat_badan" type="number" class="form-input w-full mt-1 border rounded p-2" />
          </div>
          <div>
            <label class="block font-medium">Tinggi Badan (cm)</label>
            <input v-model="form.tinggi_badan" type="number" class="form-input w-full mt-1 border rounded p-2" />
          </div>
          <div>
            <label class="block font-medium">Keadaan Jasmani</label>
            <input v-model="form.keadaan_jasmani" type="text" class="form-input w-full mt-1 border rounded p-2" />
          </div>
        </div>

        <!-- Pra Sekolah -->
        <div class="mb-4">
          <label class="block font-medium">Pernah PAUD / TK?</label>
          <input v-model="form.pra_sekolah" type="text" class="form-input w-full mt-1 border rounded p-2" />
        </div>
        <div class="mb-4">
          <label class="block font-medium">Nama PAUD / TK</label>
          <input v-model="form.nama_pra_sekolah" type="text" class="form-input w-full mt-1 border rounded p-2" />
        </div>

        <!-- KIP -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <div>
            <label class="block font-medium">Nama KIP</label>
            <input v-model="form.kip_nama" type="text" class="form-input w-full mt-1 border rounded p-2" />
          </div>
          <div>
            <label class="block font-medium">Nomor KIP</label>
            <input v-model="form.kip_nomor" type="text" class="form-input w-full mt-1 border rounded p-2" />
          </div>
        </div>

        <!-- 🔹 Kebutuhan Khusus dan Disabilitas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <div>
            <label class="block font-medium">Kebutuhan Khusus</label>
            <input v-model="form.kebutuhan_khusus" type="text" class="form-input w-full mt-1 border rounded p-2" />
          </div>
          <div>
            <label class="block font-medium">Kebutuhan Disabilitas</label>
            <input v-model="form.kebutuhan_disabilitas" type="text" class="form-input w-full mt-1 border rounded p-2" />
          </div>
        </div>

        <!-- 🔹 Tinggal Dengan, Pembiaya, Jarak ke Madrasah -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
          <div>
            <label class="block font-medium">Tinggal Dengan</label>
            <select v-model="form.tinggal_dengan" class="form-select w-full mt-1 border rounded p-2">
              <option value="">Pilih</option>
              <option value="Orang Tua">Orang Tua</option>
              <option value="Wali">Wali</option>
            </select>
            <div v-if="form.errors.tinggal_dengan" class="text-red-500 text-sm mt-1">{{ form.errors.tinggal_dengan }}</div>
          </div>

          <div>
            <label class="block font-medium">Pembiaya</label>
            <select v-model="form.pembiaya" class="form-select w-full mt-1 border rounded p-2">
              <option value="">Pilih</option>
              <option value="Orang Tua">Orang Tua</option>
              <option value="Wali">Wali</option>
            </select>
            <div v-if="form.errors.pembiaya" class="text-red-500 text-sm mt-1">{{ form.errors.pembiaya }}</div>
          </div>

          <div>
            <label class="block font-medium">Jarak ke Madrasah</label>
            <input v-model="form.jarak_ke_madrasah" type="text" class="form-input w-full mt-1 border rounded p-2" />
            <div v-if="form.errors.jarak_ke_madrasah" class="text-red-500 text-sm mt-1">{{ form.errors.jarak_ke_madrasah }}</div>
          </div>
        </div>

        <!-- 🔹 Imunisasi -->
        <div class="mb-6">
          <label class="block font-medium mb-1">Imunisasi yang Pernah Diterima</label>
          <div class="flex flex-wrap gap-4">
            <label v-for="item in ['Hepatitis', 'Campak', 'BCG', 'DPD', 'Polio', 'Covid']" :key="item"
              class="flex items-center space-x-2">
              <input type="checkbox" :value="item" v-model="form.imunisasi" />
              <span>{{ item }}</span>
            </label>
          </div>
        </div>

        <!-- 🔹 Data Orang Tua -->
        <div class="mt-8">
          <h4 class="text-lg font-semibold mb-2">Data Orang Tua</h4>

          <div v-for="(ortu, index) in form.orang_tuas" :key="index" class="mb-6 p-4 border rounded bg-gray-50">
            <h5 class="font-bold mb-2">{{ ortu.tipe }}</h5>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block">Nama</label>
                <input v-model="ortu.nama" type="text" class="form-input w-full" />
              </div>
              <div>
                <label class="block">Status</label>
                <select v-model="ortu.status" class="form-select w-full">
                  <option value="">Pilih</option>
                  <option>Masih Hidup</option>
                  <option>Sudah Meninggal</option>
                </select>
              </div>
              <div>
                <label class="block">NIK</label>
                <input v-model="ortu.nik" type="text" class="form-input w-full" />
              </div>
              <div>
                <label class="block">Tempat Lahir</label>
                <input v-model="ortu.tempat_lahir" type="text" class="form-input w-full" />
              </div>
              <div>
                <label class="block">Tanggal Lahir</label>
                <input v-model="ortu.tanggal_lahir" type="date" class="form-input w-full" />
              </div>
              <div>
                <label class="block">Pendidikan</label>
                <input v-model="ortu.pendidikan" type="text" class="form-input w-full" />
              </div>
              <div>
                <label class="block">Pekerjaan</label>
                <input v-model="ortu.pekerjaan" type="text" class="form-input w-full" />
              </div>
              <div>
                <label class="block">Penghasilan</label>
                <input v-model="ortu.penghasilan" type="text" class="form-input w-full" />
              </div>
              <div>
                <label class="block">No HP</label>
                <input v-model="ortu.no_hp" type="text" class="form-input w-full" />
              </div>
            </div>
          </div>
        </div>

        <!-- 🔹 Data Wali -->
        <div class="mt-8">
          <h4 class="text-lg font-semibold mb-2">Data Wali (Opsional)</h4>

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

        <!-- 🔹 Tombol Simpan -->
        <div class="mt-6">
          <button :disabled="form.processing" type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Simpan
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

const props = defineProps({ users: Array })

const form = useForm({
  user_id: '',
  nama: '',
  tempat_lahir: '',
  tanggal_lahir: '',
  no_kk: '',
  nik: '',
  anak_ke: '',
  jumlah_saudara: '',
  jenis_kelamin: '',
  agama: '',
  berat_badan: '',
  tinggi_badan: '',
  cita_cita: '',
  hobi: '',
  bahasa: '',
  keadaan_jasmani: '',
  alamat: '',
  provinsi: '',
  kabupaten: '',
  kecamatan: '',
  kelurahan: '',
  no_hp: '',
  tinggal_dengan: '',
  pembiaya: '',
  jarak_ke_madrasah: '',
  kebutuhan_khusus: '',
  kebutuhan_disabilitas: '',
  pra_sekolah: '',
  nama_pra_sekolah: '',
  kip_nama: '',
  kip_nomor: '',
  foto: null,
  imunisasi: [],
  orang_tuas: [
    {
      tipe: 'Ayah',
      nama: '',
      status: '',
      nik: '',
      tempat_lahir: '',
      tanggal_lahir: '',
      pendidikan: '',
      pekerjaan: '',
      penghasilan: '',
      no_hp: '',
    },
    {
      tipe: 'Ibu',
      nama: '',
      status: '',
      nik: '',
      tempat_lahir: '',
      tanggal_lahir: '',
      pendidikan: '',
      pekerjaan: '',
      penghasilan: '',
      no_hp: '',
    },
  ],
  wali: {
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
  form.post(route('pendaftar.store'), { forceFormData: true })
}

function shouldRenderField(name, value) {
  return typeof value !== 'object' && name !== 'foto' && name !== 'user_id'
}

function isNumericField(name) {
  const numericFields = [
    'anak_ke', 'jumlah_saudara', 'berat_badan', 'tinggi_badan',
    'no_kk', 'nik', 'no_hp', 'kip_nomor'
  ]
  return numericFields.includes(name)
}
</script>