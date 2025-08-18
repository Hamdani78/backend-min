<template>
  <div>
    <label :for="name" class="block text-sm font-medium text-gray-700">{{ label }}</label>

    <label
      class="mt-1 flex flex-col items-center justify-center w-full h-28 border-2 border-dashed rounded-lg cursor-pointer hover:bg-gray-50"
      :class="error ? 'border-red-300 bg-red-50/40' : 'border-gray-300'"
    >
      <input
        class="hidden"
        type="file"
        :id="name"
        :name="name"
        :required="required"
        accept=".pdf,image/*"
        @change="handleChange"
      />
      <div class="text-center px-3">
        <p class="text-sm text-gray-700">
          <span v-if="!file">Klik untuk pilih file atau tarik & lepas</span>
          <span v-else class="font-medium truncate block max-w-[220px] mx-auto">{{ file.name }}</span>
        </p>
        <p class="text-xs text-gray-500">PDF / JPG / PNG</p>
      </div>
    </label>

    <!-- Preview -->
    <div v-if="previewUrl" class="mt-2">
      <img :src="previewUrl" alt="Preview" class="w-full h-40 object-contain rounded border" />
    </div>
    <div v-else-if="file && file.type === 'application/pdf'" class="mt-2 text-xs text-gray-600">
      <span class="inline-flex items-center gap-2 px-2 py-1 bg-gray-100 rounded border">
        📄 PDF terpilih
      </span>
    </div>

    <div class="mt-2 flex items-center justify-between">
      <span v-if="file" class="text-xs text-gray-600">
        {{ Math.round(file.size / 1024) }} KB • {{ file.type || 'Unknown' }}
      </span>
      <button
        v-if="file"
        type="button"
        class="text-xs px-2 py-1 rounded border hover:bg-gray-50"
        @click="clearFile"
      >
        Hapus
      </button>
    </div>

    <p v-if="error" class="text-xs text-red-600 mt-1">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref, onBeforeUnmount } from 'vue'

defineProps({
  label: String,
  name: String,
  file: { type: [Object, File, null], default: null },
  error: { type: String, default: '' },
  required: { type: Boolean, default: false }
})
const emit = defineEmits(['change', 'clear'])

const previewUrl = ref(null)

function handleChange(e) {
  const file = e.target.files?.[0] || null
  if (previewUrl.value) URL.revokeObjectURL(previewUrl.value)
  if (file && file.type?.startsWith('image/')) {
    previewUrl.value = URL.createObjectURL(file)
  } else {
    previewUrl.value = null
  }
  emit('change', file)
}

function clearFile() {
  if (previewUrl.value) URL.revokeObjectURL(previewUrl.value)
  previewUrl.value = null
  emit('clear')
}

onBeforeUnmount(() => {
  if (previewUrl.value) URL.revokeObjectURL(previewUrl.value)
})
</script>
