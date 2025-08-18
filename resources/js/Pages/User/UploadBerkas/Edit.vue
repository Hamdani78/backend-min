<template>
  <UserLayout>
    <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
      <h2 class="text-xl font-semibold mb-4">Edit Berkas Pendaftaran</h2>

      <div v-if="$page.props.flash?.success" class="bg-green-50 text-green-800 p-3 rounded mb-4 border border-green-200">
        {{ $page.props.flash.success }}
      </div>
      <div v-if="$page.props.flash?.error" class="bg-red-50 text-red-800 p-3 rounded mb-4 border border-red-200">
        {{ $page.props.flash.error }}
      </div>

      <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-5">
        <div v-for="(label, key) in labels" :key="key" class="border rounded-lg p-3"
             :class="clientErrors[key] || form.errors[key] ? 'border-red-300 bg-red-50/40' : 'border-gray-200'">
          <label class="font-medium block text-sm text-gray-700">{{ label }}</label>

          <div v-if="berkas?.[key]" class="mt-1 text-xs">
            <a :href="`/storage/${berkas[key]}`" target="_blank" rel="noopener" class="text-blue-600 underline">
              📄 Lihat berkas lama
            </a>
          </div>

          <input class="mt-2 block" type="file" accept=".pdf,image/*" @change="onChange($event, key)" />

          <p v-if="clientErrors[key]" class="text-xs text-red-600 mt-1">{{ clientErrors[key] }}</p>
          <p v-else-if="form.errors[key]" class="text-xs text-red-600 mt-1">{{ form.errors[key] }}</p>

          <div v-if="files[key]" class="mt-2 text-xs text-gray-600">
            File baru: {{ files[key].name }} • {{ Math.round(files[key].size/1024) }} KB
          </div>
        </div>

        <div v-if="progress !== null" class="w-full">
          <div class="h-2 bg-gray-200 rounded"><div class="h-2 bg-green-600 rounded" :style="{ width: progress + '%' }"></div></div>
          <div class="text-xs text-gray-600 mt-1">Mengunggah: {{ progress }}%</div>
        </div>

        <div class="flex items-center gap-3">
          <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 disabled:opacity-60 disabled:cursor-not-allowed"
                  :disabled="disabledSubmit">
            <span v-if="!isSubmitting">Simpan Perubahan</span><span v-else>Menyimpan…</span>
          </button>
          <button type="button" class="px-4 py-2 rounded border hover:bg-gray-50" :disabled="isSubmitting" @click="resetChanges">
            Batalkan Perubahan
          </button>
        </div>

        <p class="text-xs text-gray-500">Format: PDF/JPG/PNG. Batas ukuran: 5 MB (Ijazah/Akte/KK), 2 MB (KIP).</p>
      </form>
    </div>
  </UserLayout>
</template>

<script setup>
import UserLayout from '@/Pages/User/UserLayouts/UserLayout.vue'
import { useForm } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const props = defineProps({
  pendaftar: { type: Object, required: true },
  berkas: { type: Object, default: null }
})

const labels = {
  ijazah_tk: 'Ijazah TK',
  akte_kelahiran: 'Akte Kelahiran',
  kartu_keluarga: 'Kartu Keluarga',
  kip: 'KIP (Opsional)'
}

const files = ref({ ijazah_tk:null, akte_kelahiran:null, kartu_keluarga:null, kip:null })
const isSubmitting = ref(false)
const progress = ref(null)
const clientErrors = ref({ ijazah_tk:'', akte_kelahiran:'', kartu_keluarga:'', kip:'' })

const form = useForm({
  pendaftar_id: props.pendaftar?.id ?? null,
  ijazah_tk: null, akte_kelahiran: null, kartu_keluarga: null, kip: null
})

const MAX_SIZE = { default: 5*1024*1024, kip: 2*1024*1024 }
const ALLOWED  = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg']

function validate(file, key){
  if(!file) return 'File tidak ditemukan.'
  if(!ALLOWED.includes(file.type)) return 'Tipe file harus PDF/JPG/PNG.'
  const limit = key==='kip'?MAX_SIZE.kip:MAX_SIZE.default
  if(file.size>limit) return `Ukuran file melebihi ${key==='kip'?'2':'5'} MB.`
  return ''
}
function onChange(e,key){
  const file = e.target.files?.[0] || null
  const err = file ? validate(file,key) : ''
  clientErrors.value[key] = err
  files.value[key] = err ? null : file
}

const hasChanges = computed(()=>Object.values(files.value).some(f=>!!f))
const hasClientError = computed(()=>Object.values(clientErrors.value).some(Boolean))
const disabledSubmit = computed(()=>isSubmitting.value || !hasChanges.value || hasClientError.value)

function resetChanges(){
  files.value = { ijazah_tk:null, akte_kelahiran:null, kartu_keluarga:null, kip:null }
  clientErrors.value = { ijazah_tk:'', akte_kelahiran:'', kartu_keluarga:'', kip:'' }
  form.clearErrors(); progress.value=null
}

function submit(){
  if(disabledSubmit.value) return
  isSubmitting.value = true; progress.value = 0

  // copy hanya file yang berubah ke form
  for(const k of Object.keys(files.value)){ form[k] = files.value[k] }

  // transform -> FormData (tanpa _method PUT karena route POST)
  form.transform((data)=>{
    const fd = new FormData()
    Object.entries(data).forEach(([k,v])=>{
      if(v!==null && v!==undefined) fd.append(k,v)
    })
    return fd
  })

  form.post(route('user.berkas.update'), {
    forceFormData: true,
    onProgress: p => (progress.value = p?.percentage ?? null),
    onFinish: () => { isSubmitting.value = false },
  })
}
</script>
