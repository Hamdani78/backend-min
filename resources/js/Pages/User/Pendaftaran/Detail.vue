<template>
  <UserLayout>
    <div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">
      <h2 class="text-xl font-bold mb-4">Data Pendaftaran</h2>

      <!-- Status verifikasi -->
      <div v-if="isLocked" class="mb-4 rounded border border-green-200 bg-green-50 text-green-800 px-4 py-2">
        ✔ Data sudah <strong>diverifikasi admin</strong> dan formulir terkunci.
      </div>
      <div v-if="verificationNote" class="mb-4 rounded border border-yellow-200 bg-yellow-50 text-yellow-800 px-4 py-2">
        <strong>Catatan dari admin:</strong>
        <div class="mt-1 whitespace-pre-line">{{ verificationNote }}</div>
      </div>

      <!-- 🔹 Data Siswa -->
      <section class="mb-6">
        <h3 class="font-semibold mb-2 text-gray-700">Data Siswa</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
          <table class="w-full border border-gray-300">
            <tbody>
              <tr>
                <td class="font-medium p-2 border">Nama</td>
                <td class="p-2 border">{{ pendaftar?.nama || '-' }}</td>
              </tr>
              <tr>
                <td class="font-medium p-2 border">Tempat, Tgl Lahir</td>
                <td class="p-2 border">
                  {{ pendaftar?.tempat_lahir || '-' }}, {{ fmtDate(pendaftar?.tanggal_lahir) }}
                </td>
              </tr>
              <tr>
                <td class="font-medium p-2 border">Jenis Kelamin</td>
                <td class="p-2 border">{{ jenisKelamin }}</td>
              </tr>
              <tr>
                <td class="font-medium p-2 border">Agama</td>
                <td class="p-2 border">{{ pendaftar?.agama || '-' }}</td>
              </tr>
              <tr>
                <td class="font-medium p-2 border">Bahasa</td>
                <td class="p-2 border">{{ pendaftar?.bahasa || '-' }}</td>
              </tr>
              <tr>
                <td class="font-medium p-2 border">Hobi</td>
                <td class="p-2 border">{{ pendaftar?.hobi || '-' }}</td>
              </tr>
              <tr>
                <td class="font-medium p-2 border">Cita-cita</td>
                <td class="p-2 border">{{ pendaftar?.cita_cita || '-' }}</td>
              </tr>
              <tr>
                <td class="font-medium p-2 border">Alamat</td>
                <td class="p-2 border">{{ pendaftar?.alamat || '-' }}</td>
              </tr>
              <tr>
                <td class="font-medium p-2 border">No HP</td>
                <td class="p-2 border">{{ pendaftar?.no_hp || '-' }}</td>
              </tr>
              <tr>
                <td class="font-medium p-2 border">No KK</td>
                <td class="p-2 border">{{ pendaftar?.no_kk || '-' }}</td>
              </tr>
              <tr>
                <td class="font-medium p-2 border">NIK</td>
                <td class="p-2 border">{{ pendaftar?.nik || '-' }}</td>
              </tr>
              <tr>
                <td class="font-medium p-2 border">Imunisasi</td>
                <td class="p-2 border">
                  <span v-if="Array.isArray(pendaftar?.imunisasi) && pendaftar.imunisasi.length">
                    {{ pendaftar.imunisasi.join(', ') }}
                  </span>
                  <span v-else>-</span>
                </td>
              </tr>
            </tbody>
          </table>

          <table class="w-full border border-gray-300">
            <tbody>
              <tr>
                <td class="font-medium p-2 border">Berat Badan</td>
                <td class="p-2 border">{{ numOrDash(pendaftar?.berat_badan, 'kg') }}</td>
              </tr>
              <tr>
                <td class="font-medium p-2 border">Tinggi Badan</td>
                <td class="p-2 border">{{ numOrDash(pendaftar?.tinggi_badan, 'cm') }}</td>
              </tr>
              <tr>
                <td class="font-medium p-2 border">Keadaan Jasmani</td>
                <td class="p-2 border">{{ pendaftar?.keadaan_jasmani || '-' }}</td>
              </tr>
              <tr>
                <td class="font-medium p-2 border">Pra Sekolah</td>
                <td class="p-2 border">{{ pendaftar?.pra_sekolah || '-' }}</td>
              </tr>
              <tr>
                <td class="font-medium p-2 border">Nama Pra Sekolah</td>
                <td class="p-2 border">{{ pendaftar?.nama_pra_sekolah || '-' }}</td>
              </tr>
              <tr>
                <td class="font-medium p-2 border">Kebutuhan Khusus</td>
                <td class="p-2 border">{{ pendaftar?.kebutuhan_khusus || '-' }}</td>
              </tr>
              <tr>
                <td class="font-medium p-2 border">Kebutuhan Disabilitas</td>
                <td class="p-2 border">{{ pendaftar?.kebutuhan_disabilitas || '-' }}</td>
              </tr>
              <tr>
                <td class="font-medium p-2 border">Tinggal Dengan</td>
                <td class="p-2 border">{{ pendaftar?.tinggal_dengan || '-' }}</td>
              </tr>
              <tr>
                <td class="font-medium p-2 border">Pembiaya</td>
                <td class="p-2 border">{{ pendaftar?.pembiaya || '-' }}</td>
              </tr>
              <tr>
                <td class="font-medium p-2 border">Jarak ke Madrasah</td>
                <td class="p-2 border">{{ pendaftar?.jarak_ke_madrasah || '-' }}</td>
              </tr>
              <tr>
                <td class="font-medium p-2 border">Provinsi</td>
                <td class="p-2 border">{{ pendaftar?.provinsi || '-' }}</td>
              </tr>
              <tr>
                <td class="font-medium p-2 border">Kabupaten</td>
                <td class="p-2 border">{{ pendaftar?.kabupaten || '-' }}</td>
              </tr>
              <tr>
                <td class="font-medium p-2 border">Kecamatan</td>
                <td class="p-2 border">{{ pendaftar?.kecamatan || '-' }}</td>
              </tr>
              <tr>
                <td class="font-medium p-2 border">Kelurahan</td>
                <td class="p-2 border">{{ pendaftar?.kelurahan || '-' }}</td>
              </tr>
              <tr>
                <td class="font-medium p-2 border">Anak Ke</td>
                <td class="p-2 border">{{ pendaftar?.anak_ke ?? '-' }}</td>
              </tr>
              <tr>
                <td class="font-medium p-2 border">Jumlah Saudara</td>
                <td class="p-2 border">{{ pendaftar?.jumlah_saudara ?? '-' }}</td>
              </tr>
              <tr>
                <td class="font-medium p-2 border">KIP Nama</td>
                <td class="p-2 border">{{ pendaftar?.kip_nama || '-' }}</td>
              </tr>
              <tr>
                <td class="font-medium p-2 border">KIP Nomor</td>
                <td class="p-2 border">{{ pendaftar?.kip_nomor || '-' }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-if="fotoUrl" class="mt-6">
          <p class="font-semibold">Foto:</p>
          <img :src="fotoUrl" class="w-32 rounded border mt-2" alt="Foto pendaftar" />
        </div>
      </section>

      <!-- 🔹 Orang Tua -->
      <section class="mb-6">
        <h3 class="font-semibold mb-2 text-gray-700">Data Orang Tua</h3>
        <div
          v-for="(ortu, index) in orangTuas"
          :key="index"
          class="border p-4 mb-4 rounded bg-gray-50"
        >
          <h4 class="font-bold mb-3 text-gray-800">{{ ortu?.tipe || '-' }}</h4>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <table class="w-full border border-gray-300">
              <tbody>
                <tr>
                  <td class="font-medium p-2 border">Nama</td>
                  <td class="p-2 border">{{ ortu?.nama || '-' }}</td>
                </tr>
                <tr>
                  <td class="font-medium p-2 border">Status</td>
                  <td class="p-2 border">{{ ortu?.status || '-' }}</td>
                </tr>
                <tr>
                  <td class="font-medium p-2 border">NIK</td>
                  <td class="p-2 border">{{ ortu?.nik || '-' }}</td>
                </tr>
                <tr>
                  <td class="font-medium p-2 border">Tempat, Tgl Lahir</td>
                  <td class="p-2 border">
                    {{ ortu?.tempat_lahir || '-' }}, {{ fmtDate(ortu?.tanggal_lahir) }}
                  </td>
                </tr>
              </tbody>
            </table>
            <table class="w-full border border-gray-300">
              <tbody>
                <tr>
                  <td class="font-medium p-2 border">Pendidikan</td>
                  <td class="p-2 border">{{ ortu?.pendidikan || '-' }}</td>
                </tr>
                <tr>
                  <td class="font-medium p-2 border">Pekerjaan</td>
                  <td class="p-2 border">{{ ortu?.pekerjaan || '-' }}</td>
                </tr>
                <tr>
                  <td class="font-medium p-2 border">Penghasilan</td>
                  <td class="p-2 border">{{ ortu?.penghasilan || '-' }}</td>
                </tr>
                <tr>
                  <td class="font-medium p-2 border">No HP</td>
                  <td class="p-2 border">{{ ortu?.no_hp || '-' }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- 🔹 Wali -->
      <section v-if="pendaftar?.wali" class="mb-6">
        <h3 class="font-semibold mb-2 text-gray-700">Data Wali</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
          <table class="w-full border border-gray-300">
            <tbody>
              <tr>
                <td class="font-medium p-2 border">Nama</td>
                <td class="p-2 border">{{ pendaftar?.wali?.nama || '-' }}</td>
              </tr>
            </tbody>
          </table>
          <table class="w-full border border-gray-300">
            <tbody>
              <tr>
                <td class="font-medium p-2 border">Hubungan</td>
                <td class="p-2 border">{{ pendaftar?.wali?.hubungan_keluarga || '-' }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- Edit hanya jika belum diverifikasi -->
      <div class="mt-4">
        <button
          v-if="!isLocked"
          @click="goToEditPage"
          class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600"
        >
          Edit Data
        </button>
      </div>
    </div>
  </UserLayout>
</template>

<script setup>
import UserLayout from '../UserLayouts/UserLayout.vue'
import { router } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  pendaftar: { type: Object, default: null },
})

// status verifikasi
const isLocked = computed(() => !!props.pendaftar?.is_verified)
const verificationNote = computed(() => props.pendaftar?.verification_note ?? null)

// list orang tua (server biasanya kirim snake_case: orang_tuas)
const orangTuas = computed(() => props.pendaftar?.orang_tuas ?? [])

// jenis kelamin
const jenisKelamin = computed(() => {
  const jk = props.pendaftar?.jenis_kelamin
  return jk === 'L' ? 'Laki-laki' : jk === 'P' ? 'Perempuan' : '-'
})

// foto url (prioritas: foto_url -> /storage/foto)
const fotoUrl = computed(() => {
  if (!props.pendaftar) return null
  if (props.pendaftar.foto_url) return props.pendaftar.foto_url
  if (props.pendaftar.foto) return `/storage/${props.pendaftar.foto}`
  return null
})

// formatter tanggal sederhana (DD/MM/YYYY)
function fmtDate(val) {
  if (!val) return '-'
  const d = new Date(val)
  if (isNaN(d.getTime())) return val 
  const dd = String(d.getDate()).padStart(2, '0')
  const mm = String(d.getMonth() + 1).padStart(2, '0')
  const yyyy = d.getFullYear()
  return `${dd}/${mm}/${yyyy}`
}

function numOrDash(n, unit) {
  if (n === null || n === undefined || n === '') return '-'
  return unit ? `${n} ${unit}` : String(n)
}

function goToEditPage() {
  router.visit(route('user.pendaftaran.create'))
}
</script>
