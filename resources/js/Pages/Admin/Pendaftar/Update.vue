<template>
  <AdminLayout>
    <div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">
      <h2 class="text-xl font-bold mb-6">Edit Pendaftar</h2>

      <!-- Flash -->
      <div v-if="$page.props.flash?.status" class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
        {{ $page.props.flash.status }}
      </div>
      <div v-if="$page.props.flash?.error" class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
        {{ $page.props.flash.error }}
      </div>

      <!-- Tabs -->
      <div class="flex gap-2 border-b mb-6">
        <button class="px-4 py-2" :class="tab==='data-diri' ? 'border-b-2 border-blue-600 font-semibold' : 'text-gray-500'" @click="tab='data-diri'">Data Diri</button>
        <button class="px-4 py-2" :class="tab==='orang-tua' ? 'border-b-2 border-blue-600 font-semibold' : 'text-gray-500'" @click="tab='orang-tua'">Orang Tua</button>
        <button class="px-4 py-2" :class="tab==='wali' ? 'border-b-2 border-blue-600 font-semibold' : 'text-gray-500'" @click="tab='wali'">Wali</button>
      </div>

      <form @submit.prevent="submit" enctype="multipart/form-data">
        <!-- STEP 1: DATA DIRI -->
        <section v-show="tab==='data-diri'">
          <!-- Nama + Foto -->
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
                <img :src="previewFoto" alt="Preview" class="w-full max-w-[120px] rounded border shadow" />
              </div>
              <div v-else-if="existingFoto" class="mt-2">
                <img :src="`/storage/${existingFoto}`" alt="Foto Lama" class="w-full max-w-[120px] rounded border shadow" />
              </div>
              <div v-else class="text-gray-500 italic mt-2">Belum ada foto</div>
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
              <div v-if="form.errors.tinggal_dengan" class="text-red-500 text-sm mt-1">{{ form.errors.tinggal_dengan }}</div>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
              <label class="block font-medium">Pembiaya</label>
              <select v-model="form.pembiaya" class="form-select w-full mt-1 border rounded p-2">
                <option value="">Pilih</option><option>Orang Tua</option><option>Wali</option>
              </select>
              <div v-if="form.errors.pembiaya" class="text-red-500 text-sm mt-1">{{ form.errors.pembiaya }}</div>
            </div>
            <div>
              <label class="block font-medium">Jarak ke Madrasah</label>
              <input v-model="form.jarak_ke_madrasah" class="form-input w-full mt-1 border rounded p-2" />
              <div v-if="form.errors.jarak_ke_madrasah" class="text-red-500 text-sm mt-1">{{ form.errors.jarak_ke_madrasah }}</div>
            </div>
          </div>

          <div class="mb-6">
            <label class="block font-medium mb-1">Imunisasi yang Pernah Diterima</label>
            <div class="flex flex-wrap gap-4">
              <label v-for="item in ['Hepatitis','Campak','BCG','DPD','Polio','Covid']" :key="item" class="flex items-center space-x-2">
                <input type="checkbox" :value="item" v-model="form.imunisasi" />
                <span>{{ item }}</span>
              </label>
            </div>
          </div>

          <div class="flex justify-end">
            <button type="button" class="px-4 py-2 rounded bg-blue-600 text-white" @click="tab='orang-tua'">
              Lanjut: Orang Tua →
            </button>
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
            <button type="button" class="px-4 py-2 rounded border" @click="tab='data-diri'">← Sebelumnya</button>
            <button type="button" class="px-4 py-2 rounded bg-blue-600 text-white" @click="tab='wali'">Lanjut: Wali →</button>
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
            <button type="button" class="px-4 py-2 rounded border" @click="tab='orang-tua'">← Sebelumnya</button>
            <button :disabled="form.processing" type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
              Simpan Perubahan
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
import { ref } from 'vue'

const props = defineProps({ pendaftar: Object })

// jaga-jaga data lama: ISO -> YYYY-MM-DD
const toDateInput = (v) => {
  if (!v) return ''
  if (/^\d{4}-\d{2}-\d{2}$/.test(v)) return v
  const d = new Date(v)
  return isNaN(d.getTime()) ? '' : d.toISOString().slice(0,10)
}

// simpan path foto lama terpisah, jangan kirim ini ke server
const existingFoto = ref(props.pendaftar.foto ?? '')

const form = useForm({
  user_id: props.pendaftar.user_id ?? '',
  nama: props.pendaftar.nama ?? '',
  tempat_lahir: props.pendaftar.tempat_lahir ?? '',
  tanggal_lahir: toDateInput(props.pendaftar.tanggal_lahir),

  no_kk: props.pendaftar.no_kk ?? '',
  nik: props.pendaftar.nik ?? '',
  anak_ke: props.pendaftar.anak_ke ?? '',
  jumlah_saudara: props.pendaftar.jumlah_saudara ?? '',
  jenis_kelamin: props.pendaftar.jenis_kelamin ?? '',
  agama: props.pendaftar.agama ?? '',
  berat_badan: props.pendaftar.berat_badan ?? '',
  tinggi_badan: props.pendaftar.tinggi_badan ?? '',
  cita_cita: props.pendaftar.cita_cita ?? '',
  hobi: props.pendaftar.hobi ?? '',
  bahasa: props.pendaftar.bahasa ?? '',
  keadaan_jasmani: props.pendaftar.keadaan_jasmani ?? '',
  alamat: props.pendaftar.alamat ?? '',
  provinsi: props.pendaftar.provinsi ?? '',
  kabupaten: props.pendaftar.kabupaten ?? '',
  kecamatan: props.pendaftar.kecamatan ?? '',
  kelurahan: props.pendaftar.kelurahan ?? '',
  no_hp: props.pendaftar.no_hp ?? '',
  tinggal_dengan: props.pendaftar.tinggal_dengan ?? '',
  pembiaya: props.pendaftar.pembiaya ?? '',
  jarak_ke_madrasah: props.pendaftar.jarak_ke_madrasah ?? '',
  kebutuhan_khusus: props.pendaftar.kebutuhan_khusus ?? '',
  kebutuhan_disabilitas: props.pendaftar.kebutuhan_disabilitas ?? '',
  pra_sekolah: props.pendaftar.pra_sekolah ?? '',
  nama_pra_sekolah: props.pendaftar.nama_pra_sekolah ?? '',
  kip_nama: props.pendaftar.kip_nama ?? '',
  kip_nomor: props.pendaftar.kip_nomor ?? '',

  // ⬇️ penting: hanya file baru yang dikirim
  foto: null,

  imunisasi: Array.isArray(props.pendaftar.imunisasi) ? props.pendaftar.imunisasi : [],

  orang_tuas: (props.pendaftar.orang_tuas ?? [
    { tipe:'Ayah', nama:'', status:'', nik:'', tempat_lahir:'', tanggal_lahir:'', pendidikan:'', pekerjaan:'', penghasilan:'', no_hp:'' },
    { tipe:'Ibu',  nama:'', status:'', nik:'', tempat_lahir:'', tanggal_lahir:'', pendidikan:'', pekerjaan:'', penghasilan:'', no_hp:'' },
  ]).map(o => ({ ...o, tanggal_lahir: toDateInput(o.tanggal_lahir) })),

  wali: props.pendaftar.wali ?? { nama:'', hubungan_keluarga:'' },
})

const tab = ref('data-diri')
const previewFoto = ref(null)

function handleFoto(e) {
  const file = e.target.files?.[0]
  form.foto = file || null
  previewFoto.value = file ? URL.createObjectURL(file) : null
}

function submit() {
  form.transform((data) => {
    const payload = { ...data, _method: 'put' }
    // jika tidak pilih file, hapus key foto agar tidak divalidasi sebagai "image"
    if (!(payload.foto instanceof File)) {
      delete payload.foto
    }
    return payload
  }).post(route('pendaftar.update', props.pendaftar.id), {
    forceFormData: true,
    preserveScroll: true,
  })
}
</script>
