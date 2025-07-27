<template>
  <UserLayouts>
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
        <div>
          <label class="block font-medium mb-1">Ijazah TK *</label>
          <input type="file" @change="handle($event, 'ijazah_tk')" required class="mt-1" />
        </div>
        <div>
          <label class="block font-medium mb-1">Akte Kelahiran *</label>
          <input type="file" @change="handle($event, 'akte_kelahiran')" required class="mt-1" />
        </div>
        <div>
          <label class="block font-medium mb-1">Kartu Keluarga *</label>
          <input type="file" @change="handle($event, 'kartu_keluarga')" required class="mt-1" />
        </div>
        <div>
          <label class="block font-medium mb-1">KIP (Opsional)</label>
          <input type="file" @change="handle($event, 'kip')" class="mt-1" />
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
          Simpan
        </button>
      </form>
    </div>
  </UserLayouts>
</template>

<script setup>
import UserLayouts from '@/Pages/User/UserLayouts/UserLayout.vue'
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'

const files = ref({
  ijazah_tk: null,
  akte_kelahiran: null,
  kartu_keluarga: null,
  kip: null,
})

function handle(e, key) {
  files.value[key] = e.target.files[0]
}

function submit() {
  const formData = new FormData()
  for (const key in files.value) {
    if (files.value[key]) {
      formData.append(key, files.value[key])
    }
  }
  router.post(route('upload-berkas.store'), formData)
}
</script>
