<template>
  <AdminLayout>
    <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
      <h2 class="text-xl font-bold mb-4">Tambah Data Pegawai</h2>

      <!-- Form -->
      <form @submit.prevent="submit">
        <!-- Nama -->
        <div class="mb-4">
          <label for="nama" class="block font-medium">Nama</label>
          <input
            v-model="form.nama"
            type="text"
            id="nama"
            class="form-input w-full"
            placeholder="Masukkan Nama"
          />
          <div v-if="form.errors.nama" class="text-red-500 text-sm mt-1">{{ form.errors.nama }}</div>
        </div>

        <!-- NIP -->
        <div class="mb-4">
          <label for="nip" class="block font-medium">NIP</label>
          <input
            v-model="form.nip"
            type="text"
            id="nip"
            class="form-input w-full"
            placeholder="Masukkan NIP"
          />
          <div v-if="form.errors.nip" class="text-red-500 text-sm mt-1">{{ form.errors.nip }}</div>
        </div>

        <!-- Email -->
        <div class="mb-4">
          <label for="email" class="block font-medium">Email</label>
          <input
            v-model="form.email"
            type="text"
            id="email"
            class="form-input w-full"
            placeholder="Masukkan Email"
          />
          <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</div>
        </div>

        <!-- Status -->
        <div class="mb-4">
          <label for="status" class="block font-medium">Status</label>
          <input
            v-model="form.status"
            type="text"
            id="status"
            class="form-input w-full"
            placeholder="Masukkan Status"
          />
          <div v-if="form.errors.status" class="text-red-500 text-sm mt-1">{{ form.errors.status }}</div>
        </div>

        <!-- Foto -->
        <div class="mb-4">
          <label for="foto" class="block font-medium">Foto</label>
          <input
            @change="handleFileChange"
            type="file"
            id="foto"
            class="form-input w-full"
          />
          <div v-if="form.errors.foto" class="text-red-500 text-sm mt-1">{{ form.errors.foto }}</div>
        </div>

        <!-- Submit Button -->
        <div class="mt-6">
          <button
            type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
            :disabled="form.processing"
          >
            Tambah
          </button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue' 

const form = useForm({
  nama: '',
  nip: '',
  email: '',
  status: '',
  foto: null
})

function handleFileChange(e) {
  form.foto = e.target.files[0]
}

function submit() {
  form.post(route('pegawai.store'), {
    preserveScroll: true
  })
}
</script>

<style scoped>
.form-input {
  border: 1px solid #cbd5e0;
  padding: 0.5rem;
  border-radius: 0.375rem;
}
</style>
