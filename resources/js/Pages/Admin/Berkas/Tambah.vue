<template>
  <AdminLayout>
    <div class="p-4 max-w-xl mx-auto bg-white rounded shadow">
      <h2 class="text-lg font-semibold mb-4">Upload Berkas Pendaftaran</h2>

      <!-- Flash / Error -->
      <div v-if="$page.props.flash?.success" class="bg-green-100 text-green-800 p-3 rounded mb-4">
        {{ $page.props.flash.success }}
      </div>
      <div v-if="$page.props.errors?.error" class="bg-red-100 text-red-800 p-3 rounded mb-4">
        {{ $page.props.errors.error }}
      </div>

      <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-4">
        <div>
          <label class="block mb-1">Pilih Pendaftar *</label>
          <select v-model="form.pendaftar_id" required class="w-full border rounded px-3 py-2">
            <option value="">-- Pilih --</option>
            <option v-for="p in pendaftarList" :key="p.id" :value="p.id">{{ p.nama }}</option>
          </select>
        </div>

        <div>
          <label>Ijazah TK *</label>
          <input type="file" @change="handle($event, 'ijazah_tk')" required class="mt-1" />
        </div>
        <div>
          <label>Akte Kelahiran *</label>
          <input type="file" @change="handle($event, 'akte_kelahiran')" required class="mt-1" />
        </div>
        <div>
          <label>Kartu Keluarga *</label>
          <input type="file" @change="handle($event, 'kartu_keluarga')" required class="mt-1" />
        </div>
        <div>
          <label>KIP (Opsional)</label>
          <input type="file" @change="handle($event, 'kip')" class="mt-1" />
        </div>
        <button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { router, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

const page = usePage()
const pendaftarList = page.props.pendaftars ?? []

const form = ref({
  pendaftar_id: '',
})
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
  formData.append('pendaftar_id', form.value.pendaftar_id)

  for (const key in files.value) {
    if (files.value[key]) formData.append(key, files.value[key])
  }

  router.post(route('berkas-pendaftaran.store'), formData)
}
</script>
