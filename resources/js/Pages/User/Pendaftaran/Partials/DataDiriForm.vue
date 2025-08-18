<script setup>
import { computed, ref, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  initial: { type: Object, default: null }
})

const opsiJK = [{ v: 'L', t: 'Laki-laki' }, { v: 'P', t: 'Perempuan' }]
const opsiAgama = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Lainnya']
const opsiTinggal = ['Orang Tua', 'Wali', 'Asrama', 'Saudara', 'Sendiri']
const opsiPembiaya = ['Orang Tua', 'Wali', 'Beasiswa', 'Lainnya']
const opsiJarak = ['< dari 1 km', '1 - 2 km', '> 2 km']
const opsiPraSekolah = ['TK', 'RA', 'PAUD', 'Tidak']
const opsiImunisasi = ['BCG', 'DPT', 'Polio', 'Campak', 'Hepatitis B', 'HIB', 'MMR', 'Tifoid']

const normDate = (val) => (val ? String(val).slice(0, 10) : '')

const form = useForm({
  nama: props.initial?.nama ?? '',
  tempat_lahir: props.initial?.tempat_lahir ?? '',
  tanggal_lahir: normDate(props.initial?.tanggal_lahir),
  no_kk: props.initial?.no_kk ?? '',
  nik: props.initial?.nik ?? '',
  anak_ke: props.initial?.anak_ke ?? '',
  jumlah_saudara: props.initial?.jumlah_saudara ?? '',
  jenis_kelamin: props.initial?.jenis_kelamin ?? 'L',
  agama: props.initial?.agama ?? 'Islam',
  berat_badan: props.initial?.berat_badan ?? '',
  tinggi_badan: props.initial?.tinggi_badan ?? '',
  cita_cita: props.initial?.cita_cita ?? '',
  hobi: props.initial?.hobi ?? '',
  bahasa: props.initial?.bahasa ?? '',
  keadaan_jasmani: props.initial?.keadaan_jasmani ?? '',
  alamat: props.initial?.alamat ?? '',
  provinsi: props.initial?.provinsi ?? '',
  kabupaten: props.initial?.kabupaten ?? '',
  kecamatan: props.initial?.kecamatan ?? '',
  kelurahan: props.initial?.kelurahan ?? '',
  no_hp: props.initial?.no_hp ?? '',
  tinggal_dengan: props.initial?.tinggal_dengan ?? 'Orang Tua',
  pembiaya: props.initial?.pembiaya ?? 'Orang Tua',
  jarak_ke_madrasah: props.initial?.jarak_ke_madrasah ?? '1 - 2 km',
  kebutuhan_khusus: props.initial?.kebutuhan_khusus ?? '',
  kebutuhan_disabilitas: props.initial?.kebutuhan_disabilitas ?? '',
  pra_sekolah: props.initial?.pra_sekolah ?? '',
  nama_pra_sekolah: props.initial?.nama_pra_sekolah ?? '',
  kip_nama: props.initial?.kip_nama ?? '',
  kip_nomor: props.initial?.kip_nomor ?? '',
  foto: null,
  imunisasi: Array.isArray(props.initial?.imunisasi) ? props.initial.imunisasi : [],
})

const isEdit = computed(() => !!props.initial?.id)
const isLocked = computed(() => !!props.initial?.is_verified)
const verificationNote = computed(() => props.initial?.verification_note ?? null)

const fotoPreview = ref(null)
watch(() => form.foto, (f, old) => {
  if (fotoPreview.value) URL.revokeObjectURL(fotoPreview.value)
  fotoPreview.value = f ? URL.createObjectURL(f) : null
})

const currentFotoUrl = computed(() => {
  if (fotoPreview.value) return fotoPreview.value
  if (props.initial?.foto_url) return props.initial.foto_url
  if (props.initial?.foto) return `/storage/${props.initial.foto}`
  return null
})

function toggleImunisasi(v) {
  const i = form.imunisasi.indexOf(v)
  i === -1 ? form.imunisasi.push(v) : form.imunisasi.splice(i, 1)
}
function onFotoChange(e) { form.foto = e.target.files?.[0] ?? null }

function submit() {
  if (isLocked.value) return

  form.transform((d) => {
    const out = { ...d }
    Object.keys(out).forEach(k => {
      if (typeof out[k] === 'string') {
        out[k] = out[k].trim()
        if (out[k] === '') out[k] = null
      }
    })
    if (out.tanggal_lahir) out.tanggal_lahir = String(out.tanggal_lahir).slice(0, 10)
    if (!Array.isArray(out.imunisasi)) out.imunisasi = []
    return out
  })

  const opts = {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => {
      form.clearErrors()
      form.foto = null
    },
  }

  if (isEdit.value) {
    // UPDATE → POST ke /data-diri/update (sesuai routes kita)
    form.post(route('user.pendaftaran.data_diri.update'), opts)
  } else {
    // CREATE
    form.post(route('user.pendaftaran.data_diri.store'), opts)
  }
}
</script>

<template>
  <div class="bg-white rounded shadow p-6">
    <!-- Banner status -->
    <div v-if="isLocked" class="mb-4 p-3 rounded border border-green-200 bg-green-50 text-green-800 text-sm">
      ✔ Data sudah <strong>diverifikasi admin</strong> dan tidak bisa diedit.
    </div>
    <div v-if="verificationNote" class="mb-4 p-3 rounded border border-yellow-200 bg-yellow-50 text-yellow-800 text-sm">
      <strong>Catatan dari admin:</strong>
      <div class="mt-1 whitespace-pre-line">{{ verificationNote }}</div>
    </div>

    <!-- Mass disable seluruh kontrol -->
    <fieldset :disabled="isLocked">
      <h2 class="text-lg font-semibold mb-4">Formulir Data Diri</h2>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Identitas dasar -->
        <div>
          <div class="flex justify-between items-center">
            <label class="block text-sm font-medium label-required">Nama Lengkap</label>
          </div>
          <input v-model="form.nama" class="form-input w-full mt-1 border rounded p-2" />
          <p v-if="form.errors.nama" class="text-red-600 text-sm mt-1">{{ form.errors.nama }}</p>
        </div>

        <div>
          <div class="flex justify-between items-center">
            <label class="block text-sm font-medium label-required">Tempat Lahir</label>
          </div>
          <input v-model="form.tempat_lahir" class="form-input w-full mt-1 border rounded p-2" />
          <p v-if="form.errors.tempat_lahir" class="text-red-600 text-sm mt-1">{{ form.errors.tempat_lahir }}</p>
        </div>

        <div>
          <div class="flex justify-between items-center">
            <label class="block text-sm font-medium label-required">Tanggal Lahir</label>
          </div>
          <input type="date" v-model="form.tanggal_lahir" class="form-input w-full mt-1 border rounded p-2" />
          <p v-if="form.errors.tanggal_lahir" class="text-red-600 text-sm mt-1">{{ form.errors.tanggal_lahir }}</p>
        </div>

        <div>
          <div class="flex justify-between items-center">
            <label class="block text-sm font-medium label-required">Jenis Kelamin</label>
          </div>
          <select v-model="form.jenis_kelamin" class="form-select w-full mt-1 border rounded p-2">
            <option v-for="o in opsiJK" :key="o.v" :value="o.v">{{ o.t }}</option>
          </select>
          <p v-if="form.errors.jenis_kelamin" class="text-red-600 text-sm mt-1">{{ form.errors.jenis_kelamin }}</p>
        </div>

        <!-- Nomor kependudukan -->
        <div>
          <div class="flex justify-between items-center">
            <label class="block text-sm font-medium label-required">No. KK</label>
            <span class="text-xs text-gray-500">Contoh: 1408xxxxxxxxxxxx</span>
          </div>
          <input v-model="form.no_kk" inputmode="numeric" maxlength="16" pattern="^[0-9]{16}$"
            class="form-input w-full mt-1 border rounded p-2" />
          <p v-if="form.errors.no_kk" class="text-red-600 text-sm mt-1">{{ form.errors.no_kk }}</p>
        </div>

        <div>
          <div class="flex justify-between items-center">
            <label class="block text-sm font-medium label-required">NIK</label>
            <span class="text-xs text-gray-500">Contoh: 1408xxxxxxxxxxxx</span>
          </div>
          <input v-model="form.nik" inputmode="numeric" maxlength="16" pattern="^[0-9]{16}$"
            class="form-input w-full mt-1 border rounded p-2" />
          <p v-if="form.errors.nik" class="text-red-600 text-sm mt-1">{{ form.errors.nik }}</p>
        </div>

        <!-- Urutan keluarga -->
        <div>
          <div class="flex justify-between items-center">
            <label class="block text-sm font-medium label-required">Anak ke</label>
          </div>
          <input type="number" min="1" v-model.number="form.anak_ke"
            class="form-input w-full mt-1 border rounded p-2" />
          <p v-if="form.errors.anak_ke" class="text-red-600 text-sm mt-1">{{ form.errors.anak_ke }}</p>
        </div>

        <div>
          <div class="flex justify-between items-center">
            <label class="block text-sm font-medium label-required">Jumlah Saudara</label>
          </div>
          <input type="number" min="0" v-model.number="form.jumlah_saudara"
            class="form-input w-full mt-1 border rounded p-2" />
          <p v-if="form.errors.jumlah_saudara" class="text-red-600 text-sm mt-1">{{ form.errors.jumlah_saudara }}</p>
        </div>

        <!-- Agama & bahasa -->
        <div>
          <div class="flex justify-between items-center">
            <label class="block text-sm font-medium label-required">Agama</label>
          </div>
          <select v-model="form.agama" class="form-select w-full mt-1 border rounded p-2">
            <option v-for="a in opsiAgama" :key="a" :value="a">{{ a }}</option>
          </select>
          <p v-if="form.errors.agama" class="text-red-600 text-sm mt-1">{{ form.errors.agama }}</p>
        </div>

        <div>
          <div class="flex justify-between items-center">
            <label class="block text-sm font-medium">Bahasa Sehari-hari</label>
            <span class="text-xs text-gray-500">Contoh: Indonesia / Melayu / Minang</span>
          </div>
          <input v-model="form.bahasa" class="form-input w-full mt-1 border rounded p-2" />
          <p v-if="form.errors.bahasa" class="text-red-600 text-sm mt-1">{{ form.errors.bahasa }}</p>
        </div>

        <!-- Kesehatan -->
        <div>
          <div class="flex justify-between items-center">
            <label class="block text-sm font-medium label-required">Berat Badan (kg)</label>
          </div>
          <input type="number" min="1" step="0.1" v-model.number="form.berat_badan"
            class="form-input w-full mt-1 border rounded p-2" />
          <p v-if="form.errors.berat_badan" class="text-red-600 text-sm mt-1">{{ form.errors.berat_badan }}</p>
        </div>

        <div>
          <div class="flex justify-between items-center">
            <label class="block text-sm font-medium label-required">Tinggi Badan (cm)</label>
          </div>
          <input type="number" min="1" step="0.1" v-model.number="form.tinggi_badan"
            class="form-input w-full mt-1 border rounded p-2" />
          <p v-if="form.errors.tinggi_badan" class="text-red-600 text-sm mt-1">{{ form.errors.tinggi_badan }}</p>
        </div>

        <div>
          <div class="flex justify-between items-center">
            <label class="block text-sm font-medium label-required">Keadaan Jasmani</label>
            <span class="text-xs text-gray-500">Contoh: Sehat / memakai kacamata</span>
          </div>
          <input v-model="form.keadaan_jasmani" class="form-input w-full mt-1 border rounded p-2" />
          <p v-if="form.errors.keadaan_jasmani" class="text-red-600 text-sm mt-1">{{ form.errors.keadaan_jasmani }}</p>
        </div>

        <!-- Cita-cita & hobi -->
        <div>
          <div class="flex justify-between items-center">
            <label class="block text-sm font-medium">Cita-cita</label>
            <span class="text-xs text-gray-500">Contoh: Dokter / Guru / Polisi</span>
          </div>
          <input v-model="form.cita_cita" class="form-input w-full mt-1 border rounded p-2" />
          <p v-if="form.errors.cita_cita" class="text-red-600 text-sm mt-1">{{ form.errors.cita_cita }}</p>
        </div>

        <div>
          <div class="flex justify-between items-center">
            <label class="block text-sm font-medium">Hobi</label>
            <span class="text-xs text-gray-500">Contoh: Membaca / Sepak bola</span>
          </div>
          <input v-model="form.hobi" class="form-input w-full mt-1 border rounded p-2" />
          <p v-if="form.errors.hobi" class="text-red-600 text-sm mt-1">{{ form.errors.hobi }}</p>
        </div>

        <!-- Kontak -->
        <div>
          <div class="flex justify-between items-center">
            <label class="block text-sm font-medium label-required">No. HP</label>
            <span class="text-xs text-gray-500">Contoh: 08xxxxxxxxxx</span>
          </div>
          <input v-model="form.no_hp" inputmode="tel" class="form-input w-full mt-1 border rounded p-2" />
          <p v-if="form.errors.no_hp" class="text-red-600 text-sm mt-1">{{ form.errors.no_hp }}</p>
        </div>

        <!-- Alamat -->
        <div class="md:col-span-2">
          <div class="flex justify-between items-center">
            <label class="block text-sm font-medium label-required">Alamat Lengkap</label>
          </div>
          <textarea v-model="form.alamat" class="form-textarea w-full mt-1 border rounded p-2"></textarea>
          <p v-if="form.errors.alamat" class="text-red-600 text-sm mt-1">{{ form.errors.alamat }}</p>
        </div>

        <div>
          <div class="flex justify-between items-center">
            <label class="block text-sm font-medium label-required">Provinsi</label>
          </div>
          <input v-model="form.provinsi" class="form-input w-full mt-1 border rounded p-2" />
          <p v-if="form.errors.provinsi" class="text-red-600 text-sm mt-1">{{ form.errors.provinsi }}</p>
        </div>

        <div>
          <div class="flex justify-between items-center">
            <label class="block text-sm font-medium label-required">Kabupaten/Kota</label>
          </div>
          <input v-model="form.kabupaten" class="form-input w-full mt-1 border rounded p-2" />
          <p v-if="form.errors.kabupaten" class="text-red-600 text-sm mt-1">{{ form.errors.kabupaten }}</p>
        </div>

        <div>
          <div class="flex justify-between items-center">
            <label class="block text-sm font-medium label-required">Kecamatan</label>
          </div>
          <input v-model="form.kecamatan" class="form-input w-full mt-1 border rounded p-2" />
          <p v-if="form.errors.kecamatan" class="text-red-600 text-sm mt-1">{{ form.errors.kecamatan }}</p>
        </div>

        <div>
          <div class="flex justify-between items-center">
            <label class="block text-sm font-medium label-required">Kelurahan/Desa</label>
          </div>
          <input v-model="form.kelurahan" class="form-input w-full mt-1 border rounded p-2" />
          <p v-if="form.errors.kelurahan" class="text-red-600 text-sm mt-1">{{ form.errors.kelurahan }}</p>
        </div>

        <!-- Dropdown: Tinggal, Pembiaya, Jarak -->
        <div>
          <div class="flex justify-between items-center">
            <label class="block text-sm font-medium label-required">Tinggal Dengan</label>
          </div>
          <select v-model="form.tinggal_dengan" class="form-select w-full mt-1 border rounded p-2">
            <option v-for="o in opsiTinggal" :key="o" :value="o">{{ o }}</option>
          </select>
          <p v-if="form.errors.tinggal_dengan" class="text-red-600 text-sm mt-1">{{ form.errors.tinggal_dengan }}</p>
        </div>

        <div>
          <div class="flex justify-between items-center">
            <label class="block text-sm font-medium label-required">Pembiaya</label>
          </div>
          <select v-model="form.pembiaya" class="form-select w-full mt-1 border rounded p-2">
            <option v-for="o in opsiPembiaya" :key="o" :value="o">{{ o }}</option>
          </select>
          <p v-if="form.errors.pembiaya" class="text-red-600 text-sm mt-1">{{ form.errors.pembiaya }}</p>
        </div>

        <div>
          <div class="flex justify-between items-center">
            <label class="block text-sm font-medium label-required">Jarak ke Madrasah</label>
          </div>
          <select v-model="form.jarak_ke_madrasah" class="form-select w-full mt-1 border rounded p-2">
            <option v-for="o in opsiJarak" :key="o" :value="o">{{ o }}</option>
          </select>
          <p v-if="form.errors.jarak_ke_madrasah" class="text-red-600 text-sm mt-1">{{ form.errors.jarak_ke_madrasah }}
          </p>
        </div>

        <!-- Kebutuhan khusus & disabilitas -->
        <div>
          <div class="flex justify-between items-center">
            <label class="block text-sm font-medium">Kebutuhan Khusus</label>
          </div>
          <input v-model="form.kebutuhan_khusus" class="form-input w-full mt-1 border rounded p-2" />
          <p v-if="form.errors.kebutuhan_khusus" class="text-red-600 text-sm mt-1">{{ form.errors.kebutuhan_khusus }}
          </p>
        </div>

        <div>
          <div class="flex justify-between items-center">
            <label class="block text-sm font-medium">Kebutuhan Disabilitas</label>
          </div>
          <input v-model="form.kebutuhan_disabilitas" class="form-input w-full mt-1 border rounded p-2" />
          <p v-if="form.errors.kebutuhan_disabilitas" class="text-red-600 text-sm mt-1">{{
            form.errors.kebutuhan_disabilitas }}</p>
        </div>

        <!-- Pra-sekolah (TIDAK WAJIB) -->
        <div>
          <div class="flex justify-between items-center">
            <label class="block text-sm font-medium">Pra Sekolah</label>
          </div>
          <select v-model="form.pra_sekolah" class="form-select w-full mt-1 border rounded p-2">
            <option value="">— Pilih / Kosongkan —</option>
            <option v-for="o in opsiPraSekolah" :key="o" :value="o">{{ o }}</option>
          </select>
          <p v-if="form.errors.pra_sekolah" class="text-red-600 text-sm mt-1">{{ form.errors.pra_sekolah }}</p>
        </div>

        <div>
          <div class="flex justify-between items-center">
            <label class="block text-sm font-medium">Nama Pra Sekolah</label>
          </div>
          <input v-model="form.nama_pra_sekolah" class="form-input w-full mt-1 border rounded p-2" />
          <p v-if="form.errors.nama_pra_sekolah" class="text-red-600 text-sm mt-1">{{ form.errors.nama_pra_sekolah }}
          </p>
        </div>

        <!-- KIP (opsional) -->
        <div>
          <div class="flex justify-between items-center">
            <label class="block text-sm font-medium">Nama pada KIP (opsional)</label>
          </div>
          <input v-model="form.kip_nama" class="form-input w-full mt-1 border rounded p-2" />
          <p v-if="form.errors.kip_nama" class="text-red-600 text-sm mt-1">{{ form.errors.kip_nama }}</p>
        </div>

        <div>
          <div class="flex justify-between items-center">
            <label class="block text-sm font-medium">Nomor KIP (opsional)</label>
          </div>
          <input v-model="form.kip_nomor" class="form-input w-full mt-1 border rounded p-2" />
          <p v-if="form.errors.kip_nomor" class="text-red-600 text-sm mt-1">{{ form.errors.kip_nomor }}</p>
        </div>

        <!-- Imunisasi -->
        <div class="md:col-span-2">
          <div class="flex justify-between items-center">
            <label class="block text-sm font-medium">Imunisasi (boleh pilih lebih dari satu)</label>
          </div>
          <div class="mt-2 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
            <label v-for="v in opsiImunisasi" :key="v" class="inline-flex items-center gap-2">
              <input type="checkbox" :value="v" :checked="form.imunisasi.includes(v)" @change="toggleImunisasi(v)" />
              <span class="text-sm">{{ v }}</span>
            </label>
          </div>
          <p v-if="form.errors['imunisasi']" class="text-red-600 text-sm mt-1">{{ form.errors['imunisasi'] }}</p>
        </div>

        <!-- Foto -->
        <div class="md:col-span-2">
          <label class="block text-sm font-medium">Foto 3x4</label>
          <input :key="form.processing ? 'busy' : 'idle'" type="file" accept="image/*" class="mt-1"
            @change="onFotoChange" />

          <!-- PREVIEW -->
          <div v-if="currentFotoUrl" class="mt-2 flex items-center gap-3">
            <img :src="currentFotoUrl" alt="Foto" class="h-24 w-24 object-cover rounded border" />
            <span class="text-xs text-gray-500">
              {{ fotoPreview ? 'Preview foto baru (belum disimpan)' : 'Foto saat ini' }}
            </span>
          </div>

          <p v-if="form.errors.foto" class="text-red-600 text-sm mt-1">{{ form.errors.foto }}</p>
          <p class="text-xs text-gray-500 mt-1">Format: JPG/PNG, maks. 5 MB</p>
        </div>
      </div>
    </fieldset>

    <!-- Aksi -->
    <div class="mt-6 flex gap-3">
      <button v-if="!isLocked" @click="submit" :disabled="form.processing"
        class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700 disabled:opacity-50">
        {{ form.processing ? 'Menyimpan...' : (isEdit ? 'Update Data Diri' : 'Simpan Data Diri') }}
      </button>
      <button type="button" @click="form.reset()" class="px-4 py-2 rounded border border-gray-300 hover:bg-gray-50">
        Reset
      </button>
    </div>
  </div>
</template>

<style scoped>
.label-required::after {
  content: " *";
  color: #dc2626;
}
</style>
