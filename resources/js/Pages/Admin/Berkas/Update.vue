<template>
  <AdminLayout>
    <div class="p-4 max-w-xl mx-auto bg-white rounded shadow">
      <h2 class="text-lg font-semibold mb-4">Edit Berkas</h2>

      <div v-if="$page.props.errors?.error" class="bg-red-100 text-red-800 p-3 rounded mb-4">
        {{ $page.props.errors.error }}
      </div>

      <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-4">
        <div
          v-for="field in ['ijazah_tk', 'akte_kelahiran', 'kartu_keluarga', 'kip']"
          :key="field"
        >
          <label :for="field" class="capitalize block">{{ field.replace('_', ' ') }}</label>
          <div class="flex gap-2 items-center mt-1">
            <input type="file" :id="field" @change="handle($event, field)" class="text-sm" />
            <a
              v-if="berkas[field]"
              :href="`/storage/${berkas[field]}`"
              class="text-blue-600 text-sm underline"
              target="_blank"
              rel="noopener noreferrer"
            >
              Lihat
            </a>
          </div>
        </div>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
          Perbarui
        </button>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  berkas: Object,
})

const form = useForm({
  ijazah_tk: null,
  akte_kelahiran: null,
  kartu_keluarga: null,
  kip: null,
})

function handle(event, field) {
  form[field] = event.target.files[0]
}

function submit() {
  form.transform(data => ({
    ...data,
    _method: 'put',
  })).post(route('berkas-pendaftaran.update', {
    berkas_pendaftaran: props.berkas.id  
  }), {
    forceFormData: true,
    preserveScroll: true,
    onError: (errors) => {
      console.error(errors)
    }
  })
}
</script>
