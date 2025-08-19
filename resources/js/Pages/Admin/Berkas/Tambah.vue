<template>
  <AdminLayout>
    <div class="p-4 max-w-xl mx-auto bg-white rounded shadow">
      <h2 class="text-lg font-semibold mb-4">Upload Berkas Pendaftaran</h2>

      <!-- Flash -->
      <div v-if="$page.props.flash?.success" class="bg-green-100 text-green-800 p-3 rounded mb-4">
        {{ $page.props.flash.success }}
      </div>
      <div v-if="$page.props.errors?.error" class="bg-red-100 text-red-800 p-3 rounded mb-4">
        {{ $page.props.errors.error }}
      </div>

      <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-4">
        <!-- Pendaftar -->
        <div :class="form.errors.pendaftar_id ? 'border border-red-300 rounded p-3 bg-red-50/40' : ''">
          <label class="block mb-1 text-sm font-medium">Pilih Pendaftar <span class="text-red-600">*</span></label>
          <select v-model="form.pendaftar_id" class="w-full border rounded px-3 py-2">
            <option value="">-- Pilih --</option>
            <option v-for="p in sortedPendaftar" :key="p.id" :value="p.id">{{ p.nama }}</option>
          </select>
          <p v-if="form.errors.pendaftar_id" class="text-xs text-red-600 mt-1">
            {{ form.errors.pendaftar_id }}
          </p>
        </div>

        <!-- Ijazah -->
        <div :class="(clientErrors.ijazah_tk || form.errors.ijazah_tk) ? 'border border-red-300 rounded p-3 bg-red-50/40' : ''">
          <label class="block text-sm font-medium">Ijazah TK <span class="text-red-600">*</span></label>
          <input type="file" accept=".pdf,image/*" class="mt-1" @change="onChange($event,'ijazah_tk')" />
          <p v-if="clientErrors.ijazah_tk" class="text-xs text-red-600 mt-1">{{ clientErrors.ijazah_tk }}</p>
          <p v-else-if="form.errors.ijazah_tk" class="text-xs text-red-600 mt-1">{{ form.errors.ijazah_tk }}</p>
          <div v-if="files.ijazah_tk" class="text-xs text-gray-600 mt-1">
            {{ files.ijazah_tk.name }} • {{ Math.round(files.ijazah_tk.size/1024) }} KB
          </div>
        </div>

        <!-- Akte -->
        <div :class="(clientErrors.akte_kelahiran || form.errors.akte_kelahiran) ? 'border border-red-300 rounded p-3 bg-red-50/40' : ''">
          <label class="block text-sm font-medium">Akte Kelahiran <span class="text-red-600">*</span></label>
          <input type="file" accept=".pdf,image/*" class="mt-1" @change="onChange($event,'akte_kelahiran')" />
          <p v-if="clientErrors.akte_kelahiran" class="text-xs text-red-600 mt-1">{{ clientErrors.akte_kelahiran }}</p>
          <p v-else-if="form.errors.akte_kelahiran" class="text-xs text-red-600 mt-1">{{ form.errors.akte_kelahiran }}</p>
          <div v-if="files.akte_kelahiran" class="text-xs text-gray-600 mt-1">
            {{ files.akte_kelahiran.name }} • {{ Math.round(files.akte_kelahiran.size/1024) }} KB
          </div>
        </div>

        <!-- KK -->
        <div :class="(clientErrors.kartu_keluarga || form.errors.kartu_keluarga) ? 'border border-red-300 rounded p-3 bg-red-50/40' : ''">
          <label class="block text-sm font-medium">Kartu Keluarga <span class="text-red-600">*</span></label>
          <input type="file" accept=".pdf,image/*" class="mt-1" @change="onChange($event,'kartu_keluarga')" />
          <p v-if="clientErrors.kartu_keluarga" class="text-xs text-red-600 mt-1">{{ clientErrors.kartu_keluarga }}</p>
          <p v-else-if="form.errors.kartu_keluarga" class="text-xs text-red-600 mt-1">{{ form.errors.kartu_keluarga }}</p>
          <div v-if="files.kartu_keluarga" class="text-xs text-gray-600 mt-1">
            {{ files.kartu_keluarga.name }} • {{ Math.round(files.kartu_keluarga.size/1024) }} KB
          </div>
        </div>

        <!-- KIP -->
        <div :class="(clientErrors.kip || form.errors.kip) ? 'border border-red-300 rounded p-3 bg-red-50/40' : ''">
          <label class="block text-sm font-medium">KIP (Opsional)</label>
          <input type="file" accept=".pdf,image/*" class="mt-1" @change="onChange($event,'kip')" />
          <p v-if="clientErrors.kip" class="text-xs text-red-600 mt-1">{{ clientErrors.kip }}</p>
          <p v-else-if="form.errors.kip" class="text-xs text-red-600 mt-1">{{ form.errors.kip }}</p>
          <div v-if="files.kip" class="text-xs text-gray-600 mt-1">
            {{ files.kip.name }} • {{ Math.round(files.kip.size/1024) }} KB
          </div>
        </div>

        <!-- Progress -->
        <div v-if="progress !== null" class="w-full">
          <div class="h-2 bg-gray-200 rounded">
            <div class="h-2 bg-blue-600 rounded" :style="{ width: progress + '%' }"></div>
          </div>
          <div class="text-xs text-gray-600 mt-1">Mengunggah: {{ progress }}%</div>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-2">
          <button class="bg-blue-600 text-white px-4 py-2 rounded disabled:opacity-60"
                  :disabled="!isValidToSubmit || isSubmitting">
            <span v-if="!isSubmitting">Simpan</span>
            <span v-else>Menyimpan…</span>
          </button>
          <button type="button" class="px-4 py-2 rounded border hover:bg-gray-50"
                  :disabled="isSubmitting" @click="resetForm">
            Reset
          </button>
        </div>

        <!-- Alasan tombol disable (debug) -->
        <div class="text-xs text-gray-500">
          <div v-for="(msg, i) in debugMessages" :key="i">• {{ msg }}</div>
        </div>

        <p class="text-xs text-gray-500">
          Format: PDF/JPG/PNG. Batas ukuran: 5 MB (Ijazah/Akte/KK), 2 MB (KIP).
        </p>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const props = defineProps({
  pendaftars: { type: Array, default: () => [] },
})

/* List pendaftar diurutkan */
const sortedPendaftar = computed(() =>
  [...props.pendaftars].sort((a, b) => (a.nama || '').localeCompare(b.nama || ''))
)

/* Inertia form */
const form = useForm({
  pendaftar_id: '',
  ijazah_tk: null,
  akte_kelahiran: null,
  kartu_keluarga: null,
  kip: null,
})

/* State file + error klien */
const files = ref({ ijazah_tk: null, akte_kelahiran: null, kartu_keluarga: null, kip: null })
const clientErrors = ref({ ijazah_tk: '', akte_kelahiran: '', kartu_keluarga: '', kip: '' })
const isSubmitting = ref(false)
const progress = ref(null)

/* Validasi klien */
const MAX_SIZE = { default: 5 * 1024 * 1024, kip: 2 * 1024 * 1024 }
const ALLOWED = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg']

function validate(file, key) {
  if (!file) return 'File tidak ditemukan.'
  if (!ALLOWED.includes(file.type)) return 'Tipe file harus PDF/JPG/PNG.'
  const limit = key === 'kip' ? MAX_SIZE.kip : MAX_SIZE.default
  if (file.size > limit) return `Ukuran file melebihi ${key === 'kip' ? '2' : '5'} MB.`
  return ''
}

function onChange(e, key) {
  const file = e.target.files?.[0] || null
  form.clearErrors(key)
  const err = file ? validate(file, key) : ''
  clientErrors.value[key] = err
  files.value[key] = err ? null : file
  form[key] = err ? null : file
}

/* Guard submit */
const isValidToSubmit = computed(() =>
  !!form.pendaftar_id &&
  !!files.value.ijazah_tk &&
  !!files.value.akte_kelahiran &&
  !!files.value.kartu_keluarga &&
  !clientErrors.value.ijazah_tk &&
  !clientErrors.value.akte_kelahiran &&
  !clientErrors.value.kartu_keluarga &&
  !clientErrors.value.kip
)

/* Pesan debug rapi (tanpa v-if pada v-for) */
const debugMessages = computed(() => {
  const msgs = []
  if (!form.pendaftar_id) msgs.push('Pilih pendaftar terlebih dahulu')
  if (!files.value.ijazah_tk) msgs.push('Ijazah TK belum dipilih')
  if (!files.value.akte_kelahiran) msgs.push('Akte Kelahiran belum dipilih')
  if (!files.value.kartu_keluarga) msgs.push('Kartu Keluarga belum dipilih')
  return msgs.concat(Object.values(clientErrors.value).filter(Boolean))
})

/* Reset */
function resetForm() {
  form.reset()
  files.value = { ijazah_tk: null, akte_kelahiran: null, kartu_keluarga: null, kip: null }
  clientErrors.value = { ijazah_tk: '', akte_kelahiran: '', kartu_keluarga: '', kip: '' }
  progress.value = null
}

/* Submit */
function submit() {
  if (isSubmitting.value || !isValidToSubmit.value) return
  isSubmitting.value = true
  progress.value = 0

  form.post(route('berkas-pendaftaran.store'), {
    forceFormData: true,
    preserveScroll: true,
    onProgress: p => (progress.value = p?.percentage ?? null),
    onFinish: () => {
      isSubmitting.value = false
      setTimeout(() => (progress.value = null), 600)
    },
  })
}
</script>
