<template>
  <AdminLayout title="Tambah Kegiatan">
    <form @submit.prevent="submit" class="bg-white p-6 rounded shadow space-y-6 w-full max-w-2xl mx-auto">
      <!-- Judul -->
      <h3 class="text-lg font-semibold border-b pb-2">Tambah Data Kegiatan</h3>

      <!-- Input Nama -->
      <div>
        <label class="block font-medium text-sm text-gray-700 mb-1">Nama</label>
        <input
          type="text"
          v-model="form.nama"
          class="form-input w-full rounded border-gray-300"
          placeholder="Masukkan Nama"
        />
        <div v-if="form.errors.nama" class="text-sm text-red-600 mt-1">{{ form.errors.nama }}</div>
      </div>

      <!-- Input Deskripsi -->
      <div>
        <label class="block font-medium text-sm text-gray-700 mb-1">Deskripsi</label>
        <textarea
          v-model="form.deskripsi"
          rows="4"
          class="form-textarea w-full rounded border-gray-300"
          placeholder="Masukkan Deskripsi"
        ></textarea>
        <div v-if="form.errors.deskripsi" class="text-sm text-red-600 mt-1">{{ form.errors.deskripsi }}</div>
      </div>

      <!-- Tombol Submit -->
      <div class="pt-2 border-t">
        <button
          type="submit"
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded disabled:opacity-50"
          :disabled="form.processing"
        >
          Tambah
        </button>
      </div>
    </form>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  nama: '',
  deskripsi: '',
})

function submit() {
  form.post(route('kegiatan.store'))
}
</script>
