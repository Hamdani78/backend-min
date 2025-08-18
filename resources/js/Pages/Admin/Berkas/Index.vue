<!-- resources/js/Pages/Admin/Berkas/Index.vue -->
<template>
  <AdminLayout>
    <div>
      <!-- Flash -->
      <div v-if="flashSuccess" class="bg-blue-100 text-blue-800 p-4 rounded mb-4">
        {{ flashSuccess }}
      </div>

      <!-- Card -->
      <div class="bg-white rounded shadow p-4">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold">Data Berkas Pendaftaran</h3>
          <Link :href="route('berkas-pendaftaran.create')">
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
              <i class="fa fa-plus mr-2"></i> Upload
            </button>
          </Link>
        </div>

        <!-- Table -->
        <table class="min-w-full table-auto border text-sm">
          <thead>
            <tr class="bg-gray-100 text-left">
              <th class="px-4 py-2 border">Nama Pendaftar</th>
              <th class="px-4 py-2 border">Ijazah TK</th>
              <th class="px-4 py-2 border">Akte</th>
              <th class="px-4 py-2 border">Kartu Keluarga</th>
              <th class="px-4 py-2 border">KIP</th>
              <th class="px-4 py-2 border">Status</th>
              <th class="px-4 py-2 border">Aksi</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="item in pagedRows" :key="item.id" class="hover:bg-gray-50">
              <td class="px-4 py-2 border">{{ item.pendaftar?.nama ?? '-' }}</td>

              <td class="px-4 py-2 border">
                <a v-if="item.ijazah_tk" :href="`/storage/${item.ijazah_tk}`" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline">Lihat</a>
                <span v-else class="text-gray-400 italic">-</span>
              </td>
              <td class="px-4 py-2 border">
                <a v-if="item.akte_kelahiran" :href="`/storage/${item.akte_kelahiran}`" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline">Lihat</a>
                <span v-else class="text-gray-400 italic">-</span>
              </td>
              <td class="px-4 py-2 border">
                <a v-if="item.kartu_keluarga" :href="`/storage/${item.kartu_keluarga}`" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline">Lihat</a>
                <span v-else class="text-gray-400 italic">-</span>
              </td>
              <td class="px-4 py-2 border">
                <a v-if="item.kip" :href="`/storage/${item.kip}`" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline">Lihat</a>
                <span v-else class="text-gray-400 italic">-</span>
              </td>

              <td class="px-4 py-2 border">
                <template
                  v-if="
                    !item.pendaftar?.status_pendaftaran ||
                    ['formulir','berkas'].includes(item.pendaftar.status_pendaftaran)
                  "
                >
                  <div class="flex flex-wrap gap-2">
                    <button
                      @click="verifyBerkas(item.id)"
                      class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded"
                    >
                      Verify
                    </button>
                    <button
                      @click="openModalSetWawancara(item)"
                      class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded"
                    >
                      Set Wawancara
                    </button>
                  </div>
                </template>

                <template v-else-if="item.pendaftar?.status_pendaftaran === 'berkas_terverifikasi'">
                  <div class="flex flex-wrap gap-2">
                    <button
                      @click="openModalSetWawancara(item)"
                      class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded"
                    >
                      Set Wawancara
                    </button>
                    <button
                      @click="ubahStatus(item.pendaftar.id, 'berkas')"
                      class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded"
                    >
                      Unverify
                    </button>
                  </div>
                </template>

                <!-- Wawancara dijadwalkan -->
                <template v-else-if="item.pendaftar?.status_pendaftaran === 'wawancara'">
                  <div class="flex flex-col gap-1">
                    <span class="text-xs text-gray-600">
                      {{ formatDateTime(item.pendaftar?.wawancara_at) }}
                      <template v-if="item.pendaftar?.wawancara_tempat">· {{ item.pendaftar.wawancara_tempat }}</template>
                    </span>
                    <div class="flex gap-2">
                      <button
                        @click="openModalSetWawancara(item)"
                        class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded"
                      >
                        Ubah Jadwal
                      </button>
                      <button
                        @click="ubahStatus(item.pendaftar.id, 'pengumuman')"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded"
                      >
                        Tandai Selesai Wawancara
                      </button>
                    </div>
                  </div>
                </template>

                <!-- Menunggu pengumuman -->
                <template v-else-if="item.pendaftar?.status_pendaftaran === 'pengumuman'">
                  <span class="text-yellow-700 bg-yellow-100 px-2 py-0.5 rounded text-xs border border-yellow-200">
                    Menunggu Pengumuman
                  </span>
                </template>

                <!-- Default -->
                <template v-else>
                  <span class="text-gray-400 italic">Selesai</span>
                </template>
              </td>

              <!-- Aksi edit/hapus berkas -->
              <td class="px-4 py-2 border">
                <div class="flex gap-2 flex-wrap">
                  <Link :href="route('berkas-pendaftaran.edit', item.id)" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded">
                    <i class="fa fa-edit"></i>
                  </Link>
                  <button @click="hapus(item.id)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                    <i class="fa fa-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Paginator -->
        <div class="flex items-center justify-between mt-4">
          <div class="text-sm text-gray-600">
            Showing {{ startIndex + 1 }} to {{ endIndex }} of {{ total }} entries
          </div>

          <div class="inline-flex items-center gap-1">
            <button class="px-3 py-1 border rounded disabled:opacity-50" :disabled="page === 1" @click="goTo(page - 1)">‹</button>

            <template v-for="p in pageItems" :key="`p-${p}`">
              <button
                v-if="typeof p === 'number'"
                class="px-3 py-1 border rounded"
                :class="p === page ? 'bg-gray-200 font-semibold' : 'bg-white hover:bg-gray-50'"
                @click="goTo(p)"
              >{{ p }}</button>
              <span v-else class="px-2">…</span>
            </template>

            <button class="px-3 py-1 border rounded disabled:opacity-50" :disabled="page === totalPages" @click="goTo(page + 1)">›</button>
          </div>
        </div>
      </div>
    </div>

    <!-- ===================== MODAL SET WAWANCARA ===================== -->
    <div v-if="showModal" class="fixed inset-0 z-50">
      <div class="absolute inset-0 bg-black/40" @click.self="closeModal"></div>
      <!-- dialog -->
      <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-5">
          <h3 class="text-lg font-semibold mb-3">Set Jadwal Wawancara</h3>

          <div class="space-y-3">
            <div>
              <label class="block text-sm font-medium">Tanggal & Jam</label>
              <input v-model="formW.jadwal" type="datetime-local" class="w-full border rounded px-3 py-2" />
            </div>
            <div>
              <label class="block text-sm font-medium">Tempat</label>
              <input v-model="formW.tempat" type="text" class="w-full border rounded px-3 py-2" placeholder="Contoh: Ruang Guru 2" />
            </div>
            <div>
              <label class="block text-sm font-medium">Catatan (opsional)</label>
              <textarea v-model="formW.catatan" rows="3" class="w-full border rounded px-3 py-2"></textarea>
            </div>
          </div>

          <div class="mt-4 flex justify-end gap-2">
            <button class="px-3 py-2 border rounded" :disabled="saving" @click="closeModal">Batal</button>
            <button
              class="px-3 py-2 bg-green-600 text-white rounded disabled:opacity-60"
              :disabled="saving || !formW.jadwal"
              @click="simpanWawancara"
            >
              <span v-if="!saving">Simpan</span>
              <span v-else>Menyimpan…</span>
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- =================== END MODAL SET WAWANCARA =================== -->
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { router, Link, usePage } from '@inertiajs/vue3'
import { computed, ref, watch, onMounted, onBeforeUnmount } from 'vue'

const props = defineProps({ berkas: { type: Array, default: () => [] } })
const flashSuccess = computed(() => usePage().props.value?.flash?.success)

function formatDateTime(iso) {
  if (!iso) return '-'
  try { return new Date(iso).toLocaleString('id-ID', { dateStyle: 'full', timeStyle: 'short' }) }
  catch { return iso }
}

const page = ref(1)
const perPage = ref(10)
const total = computed(() => props.berkas.length)
const totalPages = computed(() => Math.max(1, Math.ceil(total.value / perPage.value)))
const startIndex = computed(() => (page.value - 1) * perPage.value)
const endIndex = computed(() => Math.min(startIndex.value + perPage.value, total.value))
const pagedRows = computed(() => props.berkas.slice(startIndex.value, endIndex.value))
watch([() => props.berkas, perPage], () => { page.value = 1 })
const pageItems = computed(() => {
  const t = totalPages.value, p = page.value
  if (t <= 7) return Array.from({ length: t }, (_, i) => i + 1)
  const items = [1], add = v => { if (!items.includes(v) && v >= 1 && v <= t) items.push(v) }
  ;[p - 1, p, p + 1].forEach(add); items.push(t); items.sort((a, b) => a - b)
  const x = []
  for (let i = 0; i < items.length; i++) {
    x.push(items[i])
    if (i < items.length - 1 && items[i + 1] - items[i] > 1) x.push('…')
  }
  return x
})
function goTo(p){ if (p >= 1 && p <= totalPages.value) page.value = p }

/* Reload */
function reload(){ router.reload({ only: ['berkas','flash'], preserveScroll: true }) }

/* Verify / Status / Hapus */
function verifyBerkas(berkasId){
  if (!confirm('Verifikasi berkas ini?')) return
  router.post(route('berkas-pendaftaran.verify', berkasId), {}, { preserveScroll: true, onSuccess: reload })
}
function ubahStatus(pendaftarId, status){
  if (!confirm(`Yakin ingin mengubah status ke ${status}?`)) return
  router.post(route('admin.pendaftar.status.update', pendaftarId), { status }, { preserveScroll: true, onSuccess: reload })
}
function hapus(id){
  if (!confirm('Yakin ingin menghapus berkas ini?')) return
  router.delete(route('berkas-pendaftaran.destroy', id), { preserveScroll: true, onSuccess: reload })
}

/* ===== Modal Wawancara ===== */
const showModal = ref(false)
const saving = ref(false)
const formW = ref({ berkas_id: null, jadwal: '', tempat: '', catatan: '' })

// format lokal untuk <input type="datetime-local">
function toLocalInputValue(dt){
  const pad = n => String(n).padStart(2, '0')
  return `${dt.getFullYear()}-${pad(dt.getMonth()+1)}-${pad(dt.getDate())}T${pad(dt.getHours())}:${pad(dt.getMinutes())}`
}

function openModalSetWawancara(item){
  const dt = new Date()
  dt.setDate(dt.getDate() + 1)
  dt.setHours(9, 0, 0, 0)

  formW.value = {
    berkas_id: item.id,
    jadwal: toLocalInputValue(dt),              
    tempat: item.pendaftar?.wawancara_tempat || '',
    catatan: item.pendaftar?.wawancara_catatan || ''
  }
  showModal.value = true
}

function closeModal(){
  if (!saving.value) showModal.value = false
}

function simpanWawancara(){
  if (saving.value) return
  saving.value = true
  router.post(
    route('berkas-pendaftaran.wawancara', formW.value.berkas_id),
    { jadwal: formW.value.jadwal, tempat: formW.value.tempat, catatan: formW.value.catatan },
    { preserveScroll: true,
      onSuccess: () => { showModal.value = false; reload() },
      onFinish: () => { saving.value = false }
    }
  )
}

function handleKey(e){ if (e.key === 'Escape' && showModal.value) closeModal() }
onMounted(() => window.addEventListener('keydown', handleKey))
onBeforeUnmount(() => window.removeEventListener('keydown', handleKey))
</script>
