<template>
  <AdminLayout>
    <div class="p-4 max-w-xl mx-auto bg-white rounded shadow">
      <h2 class="text-lg font-semibold mb-4">Edit Berkas</h2>

      <div v-if="$page.props.errors?.error" class="bg-red-100 text-red-800 p-3 rounded mb-4">
        {{ $page.props.errors.error }}
      </div>

      <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-4">
        <div
          v-for="field in fields"
          :key="field.key"
          class="border rounded p-3"
          :class="clientErrors[field.key] || form.errors[field.key] ? 'border-red-300 bg-red-50/40' : 'border-gray-200'"
        >
          <label class="block font-medium text-sm">{{ field.label }}</label>

          <div v-if="berkas?.[field.key]" class="text-xs mt-1">
            <a :href="url(berkas[field.key])" target="_blank" class="text-blue-600 underline">📄 Lihat berkas lama</a>
          </div>

          <input type="file" accept=".pdf,image/*" class="mt-2"
                 :ref="el => inputsRef[field.key] = el" @change="onChange($event, field.key)" />

          <p v-if="clientErrors[field.key]" class="text-xs text-red-600 mt-1">{{ clientErrors[field.key] }}</p>
          <p v-else-if="form.errors[field.key]" class="text-xs text-red-600 mt-1">{{ form.errors[field.key] }}</p>

          <div v-if="files[field.key]" class="text-xs text-gray-600 mt-1">
            File baru: {{ files[field.key].name }} • {{ Math.round(files[field.key].size / 1024) }} KB
          </div>
        </div>

        <div v-if="progress !== null" class="w-full">
          <div class="h-2 bg-gray-200 rounded">
            <div class="h-2 bg-green-600 rounded" :style="{ width: progress + '%' }"></div>
          </div>
          <div class="text-xs text-gray-600 mt-1">Mengunggah: {{ progress }}%</div>
        </div>

        <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 disabled:opacity-60"
                :disabled="disabledSubmit">
          <span v-if="!isSubmitting">Simpan Perubahan</span>
          <span v-else>Menyimpan…</span>
        </button>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const props = defineProps({
  berkas: { type: Object, required: true } // { id, ijazah_tk, akte_kelahiran, kartu_keluarga, kip }
})

const fields = [
  { key: 'ijazah_tk', label: 'Ijazah TK' },
  { key: 'akte_kelahiran', label: 'Akte Kelahiran' },
  { key: 'kartu_keluarga', label: 'Kartu Keluarga' },
  { key: 'kip', label: 'KIP (Opsional)' }
]

const files = ref({ ijazah_tk: null, akte_kelahiran: null, kartu_keluarga: null, kip: null })
const clientErrors = ref({ ijazah_tk: '', akte_kelahiran: '', kartu_keluarga: '', kip: '' })
const inputsRef = ref({})

const form = useForm({
  ijazah_tk: null,
  akte_kelahiran: null,
  kartu_keluarga: null,
  kip: null
})

const isSubmitting = ref(false)
const progress = ref(null)

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
  const input = e.target
  const f = input.files?.[0] || null
  form.clearErrors(key)
  const err = f ? validate(f, key) : ''
  clientErrors.value[key] = err
  files.value[key] = err ? null : f
}

const hasChanges = computed(() => Object.values(files.value).some(Boolean))
const hasClientError = computed(() => Object.values(clientErrors.value).some(Boolean))
const disabledSubmit = computed(() => isSubmitting.value || !hasChanges.value || hasClientError.value)

const url = (p) => `/storage/${p}`

function submit() {
  if (disabledSubmit.value) return
  isSubmitting.value = true
  progress.value = 0

  for (const k of Object.keys(files.value)) form[k] = files.value[k]

  form.transform((data) => {
    const fd = new FormData()
    Object.entries(data).forEach(([k, v]) => {
      if (v !== null && v !== undefined) fd.append(k, v)
    })
    fd.append('_method', 'PUT')
    return fd
  })

  form.post(route('berkas-pendaftaran.update', { berkas_pendaftaran: props.berkas.id }), {
    forceFormData: true,
    preserveScroll: true,
    onProgress: (p) => (progress.value = p?.percentage ?? null),
    onFinish: () => { isSubmitting.value = false },
  })
}
</script>
