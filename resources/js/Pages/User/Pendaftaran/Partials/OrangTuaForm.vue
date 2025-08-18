<script setup>
import { useForm } from '@inertiajs/vue3'
import { watch } from 'vue'

const props = defineProps({
  ayah: { type: Object, default: null },
  ibu:  { type: Object, default: null },
  hasPendaftar: { type: Boolean, default: false }, // cegah submit jika data diri belum ada
  isLocked: { type: Boolean, default: false },     // kunci UI jika sudah diverifikasi
  verificationNote: { type: String, default: null }
})

const opsiStatus = ['Masih Hidup', 'Sudah Meninggal']
const opsiTempatTinggal = ['Rumah Sendiri', 'Kontrakan', 'Bersama Keluarga', 'Lainnya']
const opsiPendidikan = ['SD', 'SMP', 'SMA/SMK', 'D1/D2/D3', 'S1', 'S2', 'S3']
const opsiPenghasilan = ['0 - 2 juta rupiah', '2 - 5 juta rupiah', '> 5 juta rupiah']

const normDate = (v) => (v ? String(v).slice(0, 10) : '')

/* =========================
   AYAH
========================= */
const formAyah = useForm({
  nama: '', status: '', nik: '', tempat_lahir: '',
  tanggal_lahir: '', pendidikan: '', pekerjaan: '',
  penghasilan: '', no_hp: '', tempat_tinggal: '',
  alamat: '', provinsi: '', kabupaten: '',
  kecamatan: '', kelurahan: '', kks: '', pkh: '',
})

function hydrateAyah(v){
  formAyah.defaults({
    nama: v?.nama ?? '',
    status: v?.status ?? '',
    nik: v?.nik ?? '',
    tempat_lahir: v?.tempat_lahir ?? '',
    tanggal_lahir: normDate(v?.tanggal_lahir),
    pendidikan: v?.pendidikan ?? '',
    pekerjaan: v?.pekerjaan ?? '',
    penghasilan: v?.penghasilan ?? '',
    no_hp: v?.no_hp ?? '',
    tempat_tinggal: v?.tempat_tinggal ?? '',
    alamat: v?.alamat ?? '',
    provinsi: v?.provinsi ?? '',
    kabupaten: v?.kabupaten ?? '',
    kecamatan: v?.kecamatan ?? '',
    kelurahan: v?.kelurahan ?? '',
    kks: v?.kks ?? '',
    pkh: v?.pkh ?? '',
  })
  formAyah.reset()
}
watch(() => props.ayah, (v) => hydrateAyah(v), { immediate: true })

function submitAyah(){
  if (props.isLocked) return
  if (!props.hasPendaftar) return alert('Harap simpan Data Diri terlebih dahulu.')

  formAyah.transform((d) => {
    const out = { ...d }
    Object.keys(out).forEach(k => {
      if (typeof out[k] === 'string') {
        out[k] = out[k].trim()
        if (out[k] === '') out[k] = null
      }
    })
    if (out.tanggal_lahir) out.tanggal_lahir = String(out.tanggal_lahir).slice(0,10)
    return out
  })

  formAyah.post(route('user.pendaftaran.orang_tua.ayah.upsert'), {
    preserveScroll: true,
    onSuccess: () => formAyah.clearErrors(),
  })
}

/* =========================
   IBU
========================= */
const formIbu = useForm({
  nama: '', status: '', nik: '', tempat_lahir: '',
  tanggal_lahir: '', pendidikan: '', pekerjaan: '',
  penghasilan: '', no_hp: '', tempat_tinggal: '',
  alamat: '', provinsi: '', kabupaten: '',
  kecamatan: '', kelurahan: '', kks: '', pkh: '',
})

function hydrateIbu(v){
  formIbu.defaults({
    nama: v?.nama ?? '',
    status: v?.status ?? '',
    nik: v?.nik ?? '',
    tempat_lahir: v?.tempat_lahir ?? '',
    tanggal_lahir: normDate(v?.tanggal_lahir),
    pendidikan: v?.pendidikan ?? '',
    pekerjaan: v?.pekerjaan ?? '',
    penghasilan: v?.penghasilan ?? '',
    no_hp: v?.no_hp ?? '',
    tempat_tinggal: v?.tempat_tinggal ?? '',
    alamat: v?.alamat ?? '',
    provinsi: v?.provinsi ?? '',
    kabupaten: v?.kabupaten ?? '',
    kecamatan: v?.kecamatan ?? '',
    kelurahan: v?.kelurahan ?? '',
    kks: v?.kks ?? '',
    pkh: v?.pkh ?? '',
  })
  formIbu.reset()
}
watch(() => props.ibu,  (v) => hydrateIbu(v),  { immediate: true })

function submitIbu(){
  if (props.isLocked) return
  if (!props.hasPendaftar) return alert('Harap simpan Data Diri terlebih dahulu.')

  formIbu.transform((d) => {
    const out = { ...d }
    Object.keys(out).forEach(k => {
      if (typeof out[k] === 'string') {
        out[k] = out[k].trim()
        if (out[k] === '') out[k] = null
      }
    })
    if (out.tanggal_lahir) out.tanggal_lahir = String(out.tanggal_lahir).slice(0,10)
    return out
  })

  formIbu.post(route('user.pendaftaran.orang_tua.ibu.upsert'), {
    preserveScroll: true,
    onSuccess: () => formIbu.clearErrors(),
  })
}
</script>

<template>
  <div class="bg-white rounded shadow p-6">
    <!-- Banner status verifikasi -->
    <div v-if="isLocked" class="mb-4 p-3 rounded border border-green-200 bg-green-50 text-green-800 text-sm">
      ✔ Data sudah <strong>diverifikasi admin</strong> dan tidak bisa diedit.
    </div>
    <div v-if="verificationNote" class="mb-4 p-3 rounded border border-yellow-200 bg-yellow-50 text-yellow-800 text-sm">
      <strong>Catatan dari admin:</strong>
      <div class="mt-1 whitespace-pre-line">{{ verificationNote }}</div>
    </div>

    <h2 class="text-lg font-semibold mb-4">Data Orang Tua</h2>

    <fieldset :disabled="isLocked">
      <!-- ===== AYAH ===== -->
      <div class="border rounded p-4 mb-6">
        <h3 class="font-semibold mb-3">Data Ayah</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Nama -->
          <div>
            <div class="flex justify-between items-center">
              <label class="block text-sm font-medium label-required">Nama</label>
            </div>
            <input v-model="formAyah.nama" class="form-input w-full mt-1 border rounded p-2" placeholder="" />
            <p v-if="formAyah.errors.nama" class="text-red-600 text-sm mt-1">{{ formAyah.errors.nama }}</p>
          </div>

          <!-- Status -->
          <div>
            <div class="flex justify-between items-center">
              <label class="block text-sm font-medium label-required">Status</label>
            </div>
            <select v-model="formAyah.status" class="form-select w-full mt-1 border rounded p-2">
              <option value="">-- Pilih --</option>
              <option v-for="s in opsiStatus" :key="s" :value="s">{{ s }}</option>
            </select>
            <p v-if="formAyah.errors.status" class="text-red-600 text-sm mt-1">{{ formAyah.errors.status }}</p>
          </div>

          <!-- NIK -->
          <div>
            <div class="flex justify-between items-center">
              <label class="block text-sm font-medium">NIK</label>
              <span class="text-xs text-gray-500">Contoh: 1408xxxxxxxxxxxx</span>
            </div>
            <input v-model="formAyah.nik" inputmode="numeric" maxlength="16" class="form-input w-full mt-1 border rounded p-2" placeholder="" />
            <p v-if="formAyah.errors.nik" class="text-red-600 text-sm mt-1">{{ formAyah.errors.nik }}</p>
          </div>

          <!-- Tempat & Tanggal Lahir -->
          <div>
            <div class="flex justify-between items-center">
              <label class="block text-sm font-medium">Tempat Lahir</label>
            </div>
            <input v-model="formAyah.tempat_lahir" class="form-input w-full mt-1 border rounded p-2" placeholder="" />
          </div>
          <div>
            <div class="flex justify-between items-center">
              <label class="block text-sm font-medium">Tanggal Lahir</label>
            </div>
            <input type="date" v-model="formAyah.tanggal_lahir" class="form-input w-full mt-1 border rounded p-2" />
          </div>

          <!-- Pendidikan, Pekerjaan -->
          <div>
            <div class="flex justify-between items-center">
              <label class="block text-sm font-medium">Pendidikan</label>
            </div>
            <select v-model="formAyah.pendidikan" class="form-select w-full mt-1 border rounded p-2">
              <option value="">-- Pilih --</option>
              <option v-for="p in opsiPendidikan" :key="p" :value="p">{{ p }}</option>
            </select>
          </div>
          <div>
            <div class="flex justify-between items-center">
              <label class="block text-sm font-medium">Pekerjaan</label>
              <span class="text-xs text-gray-500">Contoh: Petani / Wiraswasta / PNS</span>
            </div>
            <input v-model="formAyah.pekerjaan" class="form-input w-full mt-1 border rounded p-2" placeholder="" />
          </div>

          <!-- Penghasilan -->
          <div>
            <div class="flex justify-between items-center">
              <label class="block text-sm font-medium label-required">Penghasilan</label>
            </div>
            <select v-model="formAyah.penghasilan" class="form-select w-full mt-1 border rounded p-2">
              <option value="">-- Pilih --</option>
              <option v-for="g in opsiPenghasilan" :key="g" :value="g">{{ g }}</option>
            </select>
            <p v-if="formAyah.errors.penghasilan" class="text-red-600 text-sm mt-1">{{ formAyah.errors.penghasilan }}</p>
          </div>

          <!-- Kontak & Domisili -->
          <div>
            <div class="flex justify-between items-center">
              <label class="block text-sm font-medium">No. HP</label>
              <span class="text-xs text-gray-500">Contoh: 08xxxxxxxxxx</span>
            </div>
            <input v-model="formAyah.no_hp" inputmode="tel" class="form-input w-full mt-1 border rounded p-2" placeholder="" />
          </div>
          <div>
            <div class="flex justify-between items-center">
              <label class="block text-sm font-medium">Tempat Tinggal</label>
            </div>
            <select v-model="formAyah.tempat_tinggal" class="form-select w-full mt-1 border rounded p-2">
              <option value="">-- Pilih --</option>
              <option v-for="o in opsiTempatTinggal" :key="o" :value="o">{{ o }}</option>
            </select>
          </div>

          <!-- Alamat -->
          <div class="md:col-span-2">
            <div class="flex justify-between items-center">
              <label class="block text-sm font-medium">Alamat</label>
            </div>
            <textarea v-model="formAyah.alamat" class="form-textarea w-full mt-1 border rounded p-2" placeholder=""></textarea>
          </div>

          <!-- Wilayah -->
          <div>
            <div class="flex justify-between items-center"><label class="block text-sm font-medium">Provinsi</label></div>
            <input v-model="formAyah.provinsi" class="form-input w-full mt-1 border rounded p-2" placeholder="" />
          </div>
          <div>
            <div class="flex justify-between items-center"><label class="block text-sm font-medium">Kabupaten</label></div>
            <input v-model="formAyah.kabupaten" class="form-input w-full mt-1 border rounded p-2" placeholder="" />
          </div>
          <div>
            <div class="flex justify-between items-center"><label class="block text-sm font-medium">Kecamatan</label></div>
            <input v-model="formAyah.kecamatan" class="form-input w-full mt-1 border rounded p-2" placeholder="" />
          </div>
          <div>
            <div class="flex justify-between items-center"><label class="block text-sm font-medium">Kelurahan</label></div>
            <input v-model="formAyah.kelurahan" class="form-input w-full mt-1 border rounded p-2" placeholder="" />
          </div>

          <!-- Bansos -->
          <div>
            <div class="flex justify-between items-center">
              <label class="block text-sm font-medium">KKS</label>
              <span class="text-xs text-gray-500">Contoh: 1234-xxxx (opsional)</span>
            </div>
            <input v-model="formAyah.kks" class="form-input w-full mt-1 border rounded p-2" placeholder="" />
          </div>
          <div>
            <div class="flex justify-between items-center">
              <label class="block text-sm font-medium">PKH</label>
              <span class="text-xs text-gray-500">Contoh: 1234-xxxx (opsional)</span>
            </div>
            <input v-model="formAyah.pkh" class="form-input w-full mt-1 border rounded p-2" placeholder="" />
          </div>
        </div>

        <div class="mt-4">
          <button v-if="!isLocked" @click="submitAyah" :disabled="formAyah.processing || !hasPendaftar" class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700 disabled:opacity-50">
            {{ formAyah.processing ? 'Menyimpan...' : 'Simpan / Update Ayah' }}
          </button>
        </div>
      </div>

      <!-- ===== IBU ===== -->
      <div class="border rounded p-4">
        <h3 class="font-semibold mb-3">Data Ibu</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Nama -->
          <div>
            <div class="flex justify-between items-center">
              <label class="block text-sm font-medium label-required">Nama</label>
            </div>
            <input v-model="formIbu.nama" class="form-input w-full mt-1 border rounded p-2" placeholder="" />
            <p v-if="formIbu.errors.nama" class="text-red-600 text-sm mt-1">{{ formIbu.errors.nama }}</p>
          </div>

          <!-- Status -->
          <div>
            <div class="flex justify-between items-center">
              <label class="block text-sm font-medium label-required">Status</label>
            </div>
            <select v-model="formIbu.status" class="form-select w-full mt-1 border rounded p-2">
              <option value="">-- Pilih --</option>
              <option v-for="s in opsiStatus" :key="s" :value="s">{{ s }}</option>
            </select>
            <p v-if="formIbu.errors.status" class="text-red-600 text-sm mt-1">{{ formIbu.errors.status }}</p>
          </div>

          <!-- NIK -->
          <div>
            <div class="flex justify-between items-center">
              <label class="block text-sm font-medium">NIK</label>
              <span class="text-xs text-gray-500">Contoh: 1408xxxxxxxxxxxx</span>
            </div>
            <input v-model="formIbu.nik" inputmode="numeric" maxlength="16" class="form-input w-full mt-1 border rounded p-2" placeholder="" />
            <p v-if="formIbu.errors.nik" class="text-red-600 text-sm mt-1">{{ formIbu.errors.nik }}</p>
          </div>

          <!-- Tempat & Tanggal Lahir -->
          <div>
            <div class="flex justify-between items-center">
              <label class="block text-sm font-medium">Tempat Lahir</label>
            </div>
            <input v-model="formIbu.tempat_lahir" class="form-input w-full mt-1 border rounded p-2" placeholder="" />
          </div>
          <div>
            <div class="flex justify-between items-center">
              <label class="block text-sm font-medium">Tanggal Lahir</label>
            </div>
            <input type="date" v-model="formIbu.tanggal_lahir" class="form-input w-full mt-1 border rounded p-2" />
          </div>

          <!-- Pendidikan, Pekerjaan -->
          <div>
            <div class="flex justify-between items-center">
              <label class="block text-sm font-medium">Pendidikan</label>
            </div>
            <select v-model="formIbu.pendidikan" class="form-select w-full mt-1 border rounded p-2">
              <option value="">-- Pilih --</option>
              <option v-for="p in opsiPendidikan" :key="p" :value="p">{{ p }}</option>
            </select>
          </div>
          <div>
            <div class="flex justify-between items-center">
              <label class="block text-sm font-medium">Pekerjaan</label>
              <span class="text-xs text-gray-500">Contoh: IRT / Karyawan</span>
            </div>
            <input v-model="formIbu.pekerjaan" class="form-input w-full mt-1 border rounded p-2" placeholder="" />
          </div>

          <!-- Penghasilan -->
          <div>
            <div class="flex justify-between items-center">
              <label class="block text-sm font-medium label-required">Penghasilan</label>
            </div>
            <select v-model="formIbu.penghasilan" class="form-select w-full mt-1 border rounded p-2">
              <option value="">-- Pilih --</option>
              <option v-for="g in opsiPenghasilan" :key="g" :value="g">{{ g }}</option>
            </select>
            <p v-if="formIbu.errors.penghasilan" class="text-red-600 text-sm mt-1">{{ formIbu.errors.penghasilan }}</p>
          </div>

          <!-- Kontak & Domisili -->
          <div>
            <div class="flex justify-between items-center">
              <label class="block text-sm font-medium">No. HP</label>
              <span class="text-xs text-gray-500">Contoh: 08xxxxxxxxxx</span>
            </div>
            <input v-model="formIbu.no_hp" inputmode="tel" class="form-input w-full mt-1 border rounded p-2" placeholder="" />
          </div>
          <div>
            <div class="flex justify-between items-center">
              <label class="block text-sm font-medium">Tempat Tinggal</label>
            </div>
            <select v-model="formIbu.tempat_tinggal" class="form-select w-full mt-1 border rounded p-2">
              <option value="">-- Pilih --</option>
              <option v-for="o in opsiTempatTinggal" :key="o" :value="o">{{ o }}</option>
            </select>
          </div>

          <!-- Alamat -->
          <div class="md:col-span-2">
            <div class="flex justify-between items-center">
              <label class="block text-sm font-medium">Alamat</label>
            </div>
            <textarea v-model="formIbu.alamat" class="form-textarea w-full mt-1 border rounded p-2" placeholder=""></textarea>
          </div>

          <!-- Wilayah -->
          <div>
            <div class="flex justify-between items-center"><label class="block text-sm font-medium">Provinsi</label></div>
            <input v-model="formIbu.provinsi" class="form-input w-full mt-1 border rounded p-2" placeholder="" />
          </div>
          <div>
            <div class="flex justify-between items-center"><label class="block text-sm font-medium">Kabupaten</label></div>
            <input v-model="formIbu.kabupaten" class="form-input w-full mt-1 border rounded p-2" placeholder="" />
          </div>
          <div>
            <div class="flex justify-between items-center"><label class="block text-sm font-medium">Kecamatan</label></div>
            <input v-model="formIbu.kecamatan" class="form-input w-full mt-1 border rounded p-2" placeholder="" />
          </div>
          <div>
            <div class="flex justify-between items-center"><label class="block text-sm font-medium">Kelurahan</label></div>
            <input v-model="formIbu.kelurahan" class="form-input w-full mt-1 border rounded p-2" placeholder="" />
          </div>

          <!-- Bansos -->
          <div>
            <div class="flex justify-between items-center">
              <label class="block text-sm font-medium">KKS</label>
              <span class="text-xs text-gray-500">Contoh: 1234-xxxx (opsional)</span>
            </div>
            <input v-model="formIbu.kks" class="form-input w-full mt-1 border rounded p-2" placeholder="" />
          </div>
          <div>
            <div class="flex justify-between items-center">
              <label class="block text-sm font-medium">PKH</label>
              <span class="text-xs text-gray-500">Contoh: 1234-xxxx (opsional)</span>
            </div>
            <input v-model="formIbu.pkh" class="form-input w-full mt-1 border rounded p-2" placeholder="" />
          </div>
        </div>

        <div class="mt-4">
          <button v-if="!isLocked" @click="submitIbu" :disabled="formIbu.processing || !hasPendaftar" class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700 disabled:opacity-50">
            {{ formIbu.processing ? 'Menyimpan...' : 'Simpan / Update Ibu' }}
          </button>
        </div>
      </div>
    </fieldset>
  </div>
</template>

<style scoped>
.label-required::after { content: " *"; color: #dc2626; }
</style>
