<template>
  <AdminLayout>
    <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
      <h2 class="text-xl font-bold mb-4">Ubah Data Pegawai</h2>

      <form @submit.prevent="submit">
        <!-- Nama -->
        <div class="mb-4">
          <label for="nama" class="block font-medium">Nama</label>
          <input v-model="form.nama" type="text" id="nama" class="form-input w-full" />
          <p v-if="form.errors.nama" class="text-red-500 text-sm">{{ form.errors.nama }}</p>
        </div>

        <!-- NIP -->
        <div class="mb-4">
          <label for="nip" class="block font-medium">NIP</label>
          <input v-model="form.nip" type="text" id="nip" class="form-input w-full" />
          <p v-if="form.errors.nip" class="text-red-500 text-sm">{{ form.errors.nip }}</p>
        </div>

        <!-- Email -->
        <div class="mb-4">
          <label for="email" class="block font-medium">Email</label>
          <input v-model="form.email" type="text" id="email" class="form-input w-full" />
          <p v-if="form.errors.email" class="text-red-500 text-sm">{{ form.errors.email }}</p>
        </div>

        <!-- Status -->
        <div class="mb-4">
          <label for="status" class="block font-medium">Status</label>
          <input v-model="form.status" type="text" id="status" class="form-input w-full" />
          <p v-if="form.errors.status" class="text-red-500 text-sm">{{ form.errors.status }}</p>
        </div>

        <!-- Foto -->
        <div class="mb-4">
          <label for="foto" class="block font-medium">Foto (kosongkan jika tidak diubah)</label>
          <input @change="handleFileChange" type="file" id="foto" class="form-input w-full" />
          <p v-if="form.errors.foto" class="text-red-500 text-sm">{{ form.errors.foto }}</p>
        </div>

        <!-- Submit -->
        <div class="mt-6">
          <button
            type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
            :disabled="form.processing"
          >
            Update
          </button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm } from '@inertiajs/vue3'
import { defineProps } from 'vue'

const props = defineProps({
  pegawai: Object,
})

const form = useForm({
  nama: props.pegawai.nama,
  nip: props.pegawai.nip,
  email: props.pegawai.email,
  status: props.pegawai.status,
  foto: null,
})

function handleFileChange(e) {
  form.foto = e.target.files[0]
}

function submit() {
  const data = {
    ...form.data(),
    _method: 'put', // override ke PUT
  }

  form.post(route('pegawai.update', props.pegawai.id), {
    data,
    forceFormData: true,
    preserveScroll: true,
  })
}
</script>

