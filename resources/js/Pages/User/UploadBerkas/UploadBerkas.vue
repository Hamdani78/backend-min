<template>
  <UserLayout>
    <div class="p-6 max-w-2xl mx-auto bg-white rounded-xl shadow-md">
      <h2 class="text-xl font-semibold mb-1">Upload Berkas Pendaftaran</h2>
      <p class="text-sm text-gray-600 mb-5">
        Format: <span class="font-medium">PDF / JPG / PNG</span>.
        Batas ukuran: <span class="font-medium">5 MB</span> (Ijazah/Akte/KK) dan <span class="font-medium">2 MB</span> (KIP).
      </p>

      <div v-if="$page.props.flash?.success" class="bg-green-50 text-green-800 p-3 rounded mb-4 border border-green-200">
        {{ $page.props.flash.success }}
      </div>
      <div v-if="$page.props.flash?.error" class="bg-red-50 text-red-800 p-3 rounded mb-4 border border-red-200">
        {{ $page.props.flash.error }}
      </div>

      <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <FileInput
            label="Ijazah TK *" name="ijazah_tk"
            :file="form.ijazah_tk" :error="form.errors.ijazah_tk || clientErrors.ijazah_tk" required
            @change="onFileChange('ijazah_tk', $event)" @clear="clearFile('ijazah_tk')"
          />
          <FileInput
            label="Akte Kelahiran *" name="akte_kelahiran"
            :file="form.akte_kelahiran" :error="form.errors.akte_kelahiran || clientErrors.akte_kelahiran" required
            @change="onFileChange('akte_kelahiran', $event)" @clear="clearFile('akte_kelahiran')"
          />
          <FileInput
            label="Kartu Keluarga *" name="kartu_keluarga"
            :file="form.kartu_keluarga" :error="form.errors.kartu_keluarga || clientErrors.kartu_keluarga" required
            @change="onFileChange('kartu_keluarga', $event)" @clear="clearFile('kartu_keluarga')"
          />
          <FileInput
            label="KIP (Opsional)" name="kip"
            :file="form.kip" :error="form.errors.kip || clientErrors.kip"
            @change="onFileChange('kip', $event)" @clear="clearFile('kip')"
          />
        </div>

        <div v-if="progress !== null" class="w-full">
          <div class="h-2 bg-gray-200 rounded">
            <div class="h-2 bg-blue-600 rounded" :style="{ width: progress + '%' }"></div>
          </div>
          <div class="text-xs text-gray-600 mt-1">Mengunggah: {{ progress }}%</div>
        </div>

        <div class="flex items-center gap-3">
          <button
            type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:opacity-60 disabled:cursor-not-allowed"
            :disabled="isSubmitting || !isValidToSubmit"
          >
            <span v-if="!isSubmitting">Simpan</span><span v-else>Menyimpan…</span>
          </button>
          <button type="button" class="px-4 py-2 rounded border hover:bg-gray-50" :disabled="isSubmitting" @click="resetForm">
            Reset
          </button>
        </div>
      </form>
    </div>
  </UserLayout>
</template>

<script setup>
import UserLayout from '@/Pages/User/UserLayouts/UserLayout.vue'
import FileInput from '@/Components/FileInput.vue'
import { useForm, usePage, router } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const page = usePage()
const pendaftar = page.props.pendaftar

const form = useForm({
  pendaftar_id: pendaftar?.id ?? null,
  ijazah_tk: null, akte_kelahiran: null, kartu_keluarga: null, kip: null,
})

const isSubmitting = ref(false)
const progress = ref(null)
const clientErrors = ref({ ijazah_tk:'', akte_kelahiran:'', kartu_keluarga:'', kip:'' })

const MAX_SIZE = { default: 5*1024*1024, kip: 2*1024*1024 }
const ALLOWED  = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg']

function validateFile(file, key) {
  if (!file) return 'File tidak ditemukan.'
  if (!ALLOWED.includes(file.type)) return 'Tipe file harus PDF/JPG/PNG.'
  const limit = key === 'kip' ? MAX_SIZE.kip : MAX_SIZE.default
  if (file.size > limit) return `Ukuran file melebihi ${key==='kip'?'2':'5'} MB.`
  return ''
}
function onFileChange(key, file) {
  const err = file ? validateFile(file, key) : ''
  clientErrors.value[key] = err
  form[key] = err ? null : file
}
function clearFile(key) { clientErrors.value[key]=''; form[key]=null }

const isValidToSubmit = computed(() =>
  !!form.ijazah_tk && !!form.akte_kelahiran && !!form.kartu_keluarga &&
  !clientErrors.value.ijazah_tk && !clientErrors.value.akte_kelahiran &&
  !clientErrors.value.kartu_keluarga && !clientErrors.value.kip
)

function resetForm() {
  form.reset()
  clientErrors.value = { ijazah_tk:'', akte_kelahiran:'', kartu_keluarga:'', kip:'' }
  progress.value = null
}

function submit() {
  if (isSubmitting.value || !isValidToSubmit.value) return
  isSubmitting.value = true
  progress.value = 0

  form.post(route('user.berkas.store'), {
    forceFormData: true,
    onProgress: p => (progress.value = p?.percentage ?? null),
    onSuccess: () => router.visit(route('user.berkas.show')),
    onFinish: () => { isSubmitting.value = false; setTimeout(()=>progress.value=null,600) }
  })
}
</script>

<style scoped>
label[for]{cursor:pointer}
</style>
