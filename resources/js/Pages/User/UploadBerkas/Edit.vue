<template>
  <UserLayout>
    <div class="p-4 max-w-xl mx-auto bg-white rounded shadow">
      <h2 class="text-lg font-semibold mb-4">Edit Berkas Pendaftaran</h2>

      <!-- Flash -->
      <div v-if="$page.props.flash?.success" class="bg-green-100 text-green-800 p-3 rounded mb-4">
        {{ $page.props.flash.success }}
      </div>
      <div v-if="$page.props.flash?.error" class="bg-red-100 text-red-800 p-3 rounded mb-4">
        {{ $page.props.flash.error }}
      </div>

      <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-4">
        <div v-for="(label, key) in labels" :key="key">
          <label class="font-medium block">{{ label }}</label>

          <div v-if="berkas && berkas[key]" class="mb-1 text-sm">
            <a :href="`/storage/${berkas[key]}`" target="_blank" class="text-blue-600 underline">
              📄 Lihat berkas lama
            </a>
          </div>

          <input type="file" @change="handle($event, key)" class="mt-1" />
        </div>

        <button :disabled="isSubmitting"
          class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed">
          Simpan Perubahan
        </button>
      </form>
    </div>
  </UserLayout>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import UserLayout from '@/Pages/User/UserLayouts/UserLayout.vue'

const props = defineProps({
  pendaftar: Object,
  berkas: Object
})

const files = ref({
  ijazah_tk: null,
  akte_kelahiran: null,
  kartu_keluarga: null,
  kip: null,
})

const isSubmitting = ref(false)

const labels = {
  ijazah_tk: 'Ijazah TK *',
  akte_kelahiran: 'Akte Kelahiran *',
  kartu_keluarga: 'Kartu Keluarga *',
  kip: 'KIP (Opsional)',
}

function handle(e, key) {
  files.value[key] = e.target.files[0]
}

function submit() {
  if (isSubmitting.value) return
  isSubmitting.value = true

  const formData = new FormData()
  formData.append('pendaftar_id', props.pendaftar.id)

  for (const key in files.value) {
    if (files.value[key]) {
      formData.append(key, files.value[key])
    }
  }

  router.post(route('user.berkas.update'), formData, {
    onFinish: () => {
      isSubmitting.value = false
      router.visit(route('user.berkas.show'))
    }
  })
}
</script>
