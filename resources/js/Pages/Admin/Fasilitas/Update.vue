<template>
  <AdminLayout title="Ubah Fasilitas">
    <form @submit.prevent="submit" class="bg-white p-6 rounded shadow space-y-6 w-full max-w-2xl mx-auto">
      <!-- Judul -->
      <h3 class="text-lg font-semibold border-b pb-2">Ubah Data Fasilitas</h3>

      <!-- Input Nama -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
        <input
          type="text"
          v-model="form.nama"
          class="form-input w-full rounded border-gray-300"
          placeholder="Masukkan Nama Fasilitas"
        />
        <p v-if="form.errors.nama" class="text-sm text-red-600 mt-1">{{ form.errors.nama }}</p>
      </div>

      <!-- Input Deskripsi -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
        <textarea
          v-model="form.deskripsi"
          rows="4"
          class="form-textarea w-full rounded border-gray-300"
          placeholder="Masukkan Deskripsi"
        ></textarea>
        <p v-if="form.errors.deskripsi" class="text-sm text-red-600 mt-1">{{ form.errors.deskripsi }}</p>
      </div>

      <!-- Tombol Submit -->
      <div class="pt-4 border-t">
        <button
          type="submit"
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded disabled:opacity-50"
          :disabled="form.processing"
        >
          Update
        </button>
      </div>
    </form>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm } from '@inertiajs/vue3'

// gunakan const props = defineProps()
const props = defineProps({
  fasilitas: Object
})

// gunakan props.fasilitas saat inisialisasi form
const form = useForm({
  nama: props.fasilitas.nama,
  deskripsi: props.fasilitas.deskripsi,
})

function submit() {
  form.put(route('fasilitas.update', props.fasilitas.id))
}
</script>
