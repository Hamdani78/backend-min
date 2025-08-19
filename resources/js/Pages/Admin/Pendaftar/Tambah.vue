<!-- resources/js/Pages/Admin/Pendaftar/Tambah.vue -->
<template>
  <AdminLayout>
    <div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">
      <h2 class="text-xl font-bold mb-6">Tambah Pendaftar</h2>

      <!-- Tabs -->
      <div class="flex gap-2 border-b mb-6">
        <button
          class="px-4 py-2"
          :class="tab==='data-diri' ? 'border-b-2 border-blue-600 font-semibold' : 'text-gray-500'"
          @click="go('data-diri')"
        >Data Diri</button>

        <button
          class="px-4 py-2"
          :class="tab==='orang-tua' ? 'border-b-2 border-blue-600 font-semibold' : 'text-gray-500'"
          :disabled="!canOpenOrtu"
          :title="!canOpenOrtu ? 'Lengkapi Data Diri lebih dulu' : ''"
          @click="go('orang-tua')"
        >Orang Tua</button>

        <button
          class="px-4 py-2"
          :class="tab==='wali' ? 'border-b-2 border-blue-600 font-semibold' : 'text-gray-500'"
          :disabled="!canOpenWali"
          :title="!canOpenWali ? 'Lengkapi Data Diri lebih dulu' : ''"
          @click="go('wali')"
        >Wali</button>
      </div>

      <form @submit.prevent="submit" enctype="multipart/form-data">
        <!-- STEP 1: DATA DIRI -->
        <section v-show="tab==='data-diri'">
          <!-- User -->
          <div class="mb-4">
            <label class="block font-medium">User</label>
            <select v-model="form.user_id" class="form-select w-full mt-1 border rounded p-2">
              <option value="">-- Pilih User --</option>
              <option v-for="u in props.users" :key="u.id" :value="u.id">{{ u.name }}</option>
            </select>
            <p v-if="form.errors.user_id" class="text-red-500 text-sm mt-1">{{ form.errors.user_id }}</p>
          </div>

          <!-- Nama + Foto -->
          <div class="mb-4 flex flex-col md:flex-row md:items-start gap-4">
            <div class="flex-1">
              <label class="block font-medium">Nama</label>
              <input v-model="form.nama" type="text" class="form-input w-full mt-1 border rounded p-2" />
              <p v-if="form.errors.nama" class="text-red-500 text-sm mt-1">{{ form.errors.nama }}</p>
            </div>

            <div class="w-full md:w-1/3">
              <label class="block font-medium">Foto 3x4</label>
              <input type="file" @change="handleFoto" accept="image/*" class="mt-2" />
              <div v-if="previewFoto" class="mt-2">
                <img :src="previewFoto" alt="Preview Foto" class="w-full max-w-[120px] rounded border shadow" />
              </div>
              <p v-if="form.errors.foto" class="text-red-500 text-sm mt-1">{{ form.errors.foto }}</p>
            </div>
          </div>

          <!-- Data dasar -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
              <label class="block font-medium">Tempat Lahir</label>
              <input v-model="form.tempat_lahir" type="text" class="form-input w-full mt-1 border rounded p-2" />
            </div>
            <div>
              <label class="block font-medium">Tanggal Lahir</label>
              <input v-model="form.tanggal_lahir" type="date" class="form-input w-full mt-1 border rounded p-2" />
              <p v-if="form.errors.tanggal_lahir" class="text-red-500 text-sm mt-1">{{ form.errors.tanggal_lahir }}</p>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
              <label class="block font-medium">Jenis Kelamin</label>
              <select v-model="form.jenis_kelamin" class="form-select w-full mt-1 border rounded p-2">
                <option value="">Pilih</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
              </select>
            </div>
            <div>
              <label class="block font-medium">Agama</label>
              <input v-model="form.agama" type="text" class="form-input w-full mt-1 border rounded p-2" />
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
              <label class="block font-medium">Hobi</label>
              <input v-model="form.hobi" type="text" class="form-input w-full mt-1 border rounded p-2" />
            </div>
            <div>
              <label class="block font-medium">Bahasa Sehari-hari</label>
              <input v-model="form.bahasa" type="text" class="form-input w-full mt-1 border rounded p-2" />
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
              <label class="block font-medium">Cita-cita</label>
              <input v-model="form.cita_cita" type="text" class="form-input w-full mt-1 border rounded p-2" />
            </div>
            <div>
              <label class="block font-medium">No HP</label>
              <input v-model="form.no_hp" type="text" class="form-input w-full mt-1 border rounded p-2" />
            </div>
          </div>

          <div class="mb-4">
            <label class="block font-medium">Alamat Lengkap</label>
            <textarea v-model="form.alamat" rows="3" class="form-textarea w-full mt-1 border rounded p-2"></textarea>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
            <div><label class="block font-medium">Provinsi</label><input v-model="form.provinsi" class="form-input w-full mt-1 border rounded p-2" /></div>
            <div><label class="block font-medium">Kab/Kota</label><input v-model="form.kabupaten" class="form-input w-full mt-1 border rounded p-2" /></div>
            <div><label class="block font-medium">Kecamatan</label><input v-model="form.kecamatan" class="form-input w-full mt-1 border rounded p-2" /></div>
            <div><label class="block font-medium">Kelurahan</label><input v-model="form.kelurahan" class="form-input w-full mt-1 border rounded p-2" /></div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div><label class="block font-medium">No KK</label><input v-model="form.no_kk" class="form-input w-full mt-1 border rounded p-2" /></div>
            <div><label class="block font-medium">NIK Anak</label><input v-model="form.nik" class="form-input w-full mt-1 border rounded p-2" /></div>
            <div><label class="block font-medium">Anak Ke</label><input v-model.number="form.anak_ke" type="number" min="1" class="form-input w-full mt-1 border rounded p-2" /></div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div><label class="block font-medium">Jumlah Saudara</label><input v-model.number="form.jumlah_saudara" type="number" min="0" class="form-input w-full mt-1 border rounded p-2" /></div>
            <div><label class="block font-medium">Berat (kg)</label><input v-model.number="form.berat_badan" type="number" min="0" class="form-input w-full mt-1 border rounded p-2" /></div>
            <div><label class="block font-medium">Tinggi (cm)</label><input v-model.number="form.tinggi_badan" type="number" min="0" class="form-input w-full mt-1 border rounded p-2" /></div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div><label class="block font-medium">Keadaan Jasmani</label><input v-model="form.keadaan_jasmani" class="form-input w-full mt-1 border rounded p-2" /></div>
            <div><label class="block font-medium">Pra Sekolah</label><input v-model="form.pra_sekolah" class="form-input w-full mt-1 border rounded p-2" /></div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div><label class="block font-medium">Nama Pra Sekolah</label><input v-model="form.nama_pra_sekolah" class="form-input w-full mt-1 border rounded p-2" /></div>
            <div><label class="block font-medium">Kebutuhan Khusus</label><input v-model="form.kebutuhan_khusus" class="form-input w-full mt-1 border rounded p-2" /></div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div><label class="block font-medium">Kebutuhan Disabilitas</label><input v-model="form.kebutuhan_disabilitas" class="form-input w-full mt-1 border rounded p-2" /></div>
            <div>
              <label class="block font-medium">Tinggal Dengan</label>
              <select v-model="form.tinggal_dengan" class="form-select w-full mt-1 border rounded p-2">
                <option value="">Pilih</option><option>Orang Tua</option><option>Wali</option>
              </select>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
              <label class="block font-medium">Pembiaya</label>
              <select v-model="form.pembiaya" class="form-select w-full mt-1 border rounded p-2">
                <option value="">Pilih</option><option>Orang Tua</option><option>Wali</option>
              </select>
            </div>
            <div><label class="block font-medium">Jarak ke Madrasah</label><input v-model="form.jarak_ke_madrasah" class="form-input w-full mt-1 border rounded p-2" /></div>
          </div>

          <div class="mb-6">
            <label class="block font-medium mb-1">Imunisasi</label>
            <div class="flex flex-wrap gap-4">
              <label v-for="item in opsiImunisasi" :key="item" class="flex items-center space-x-2">
                <input type="checkbox" :value="item" v-model="form.imunisasi" />
                <span>{{ item }}</span>
              </label>
            </div>
          </div>

          <div class="flex justify-end">
            <button
              type="button"
              class="px-4 py-2 rounded bg-blue-600 text-white"
              :disabled="!canOpenOrtu"
              @click="go('orang-tua')"
            >Lanjut: Orang Tua →</button>
          </div>
        </section>

        <!-- STEP 2: ORANG TUA -->
        <section v-show="tab==='orang-tua'">
          <h4 class="text-lg font-semibold mb-3">Data Orang Tua</h4>

          <div v-for="(ortu, i) in form.orang_tuas" :key="i" class="mb-6 p-4 border rounded bg-gray-50">
            <h5 class="font-bold mb-2">{{ ortu.tipe }}</h5>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div><label class="block">Nama</label><input v-model="ortu.nama" class="form-input w-full" /></div>
              <div><label class="block">Status</label>
                <select v-model="ortu.status" class="form-select w-full">
                  <option value="">Pilih</option><option>Masih Hidup</option><option>Sudah Meninggal</option>
                </select>
              </div>
              <div><label class="block">NIK</label><input v-model="ortu.nik" class="form-input w-full" /></div>
              <div><label class="block">Tempat Lahir</label><input v-model="ortu.tempat_lahir" class="form-input w-full" /></div>
              <div><label class="block">Tanggal Lahir</label><input v-model="ortu.tanggal_lahir" type="date" class="form-input w-full" /></div>
              <div><label class="block">Pendidikan</label><input v-model="ortu.pendidikan" class="form-input w-full" /></div>
              <div><label class="block">Pekerjaan</label><input v-model="ortu.pekerjaan" class="form-input w-full" /></div>
              <div><label class="block">Penghasilan</label><input v-model="ortu.penghasilan" class="form-input w-full" /></div>
              <div><label class="block">No HP</label><input v-model="ortu.no_hp" class="form-input w-full" /></div>
            </div>
          </div>

          <div class="flex justify-between">
            <button type="button" class="px-4 py-2 rounded border" @click="go('data-diri')">← Sebelumnya</button>
            <button type="button" class="px-4 py-2 rounded bg-blue-600 text-white" @click="go('wali')">
              Lanjut: Wali →
            </button>
          </div>
        </section>

        <!-- STEP 3: WALI -->
        <section v-show="tab==='wali'">
          <h4 class="text-lg font-semibold mb-3">Data Wali (Opsional)</h4>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><label class="block">Nama Wali</label><input v-model="form.wali.nama" class="form-input w-full" /></div>
            <div><label class="block">Hubungan Keluarga</label><input v-model="form.wali.hubungan_keluarga" class="form-input w-full" /></div>
          </div>

          <div class="flex justify-between mt-6">
            <button type="button" class="px-4 py-2 rounded border" @click="go('orang-tua')">← Sebelumnya</button>
            <button :disabled="form.processing" type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
              Simpan
            </button>
          </div>
        </section>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const props = defineProps({
  users: { type: Array, default: () => [] }
})

const opsiImunisasi = ['Hepatitis','Campak','BCG','DPD','Polio','Covid']

const form = useForm({
  user_id: '',
  nama: '',
  tempat_lahir: '',
  tanggal_lahir: '', // HTML date akan kirim YYYY-MM-DD
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
    { tipe: 'Ayah', nama: '', status: '', nik: '', tempat_lahir: '', tanggal_lahir: '', pendidikan: '', pekerjaan: '', penghasilan: '', no_hp: '' },
    { tipe: 'Ibu',  nama: '', status: '', nik: '', tempat_lahir: '', tanggal_lahir: '', pendidikan: '', pekerjaan: '', penghasilan: '', no_hp: '' },
  ],
  wali: { nama: '', hubungan_keluarga: '' },
})

/* Tabs */
const tab = ref('data-diri')
const canOpenOrtu  = computed(() => !!form.user_id && !!form.nama && !!form.tanggal_lahir)
const canOpenWali  = canOpenOrtu
function go(next){
  if (next==='orang-tua' && !canOpenOrtu.value) return
  if (next==='wali'       && !canOpenWali.value) return
  tab.value = next
}

/* Foto preview */
const previewFoto = ref(null)
function handleFoto(e){
  const f = e.target.files?.[0]
  form.foto = f || null
  previewFoto.value = f ? URL.createObjectURL(f) : null
}

/* Submit */
function submit(){
  form.post(route('pendaftar.store'), {
    forceFormData: true,
    preserveScroll: true,
  })
}
</script>
