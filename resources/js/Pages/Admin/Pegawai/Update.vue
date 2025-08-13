<template>
  <AdminLayout>
    <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
      <h2 class="text-xl font-bold mb-4">Ubah Data Pegawai</h2>

      <form @submit.prevent="submit" enctype="multipart/form-data">
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
          <input v-model="form.email" type="email" id="email" class="form-input w-full" />
          <p v-if="form.errors.email" class="text-red-500 text-sm">{{ form.errors.email }}</p>
        </div>

        <!-- Bidang Ajar (ganti 'status') -->
        <div class="mb-4">
          <label for="bidang_ajar" class="block font-medium">Bidang Ajar</label>
          <input v-model="form.bidang_ajar" type="text" id="bidang_ajar" class="form-input w-full" />
          <p v-if="form.errors.bidang_ajar" class="text-red-500 text-sm">{{ form.errors.bidang_ajar }}</p>
        </div>

        <!-- Keaktifan -->
        <div class="mb-4">
          <label class="block font-medium">Status Keaktifan</label>
          <select v-model="form.is_active" class="form-input w-full mt-1">
            <option :value="true">Aktif </option>
            <option :value="false">Non-aktif </option>
          </select>
          <p v-if="form.errors.is_active" class="text-red-500 text-sm">{{ form.errors.is_active }}</p>
        </div>

        <!-- Foto -->
        <div class="mb-4">
          <label for="foto" class="block font-medium">Foto (kosongkan jika tidak diubah)</label>
          <input @change="handleFileChange" type="file" id="foto" accept="image/*" class="form-input w-full" />
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

const props = defineProps({ pegawai: Object })

const form = useForm({
  nama: props.pegawai?.nama ?? '',
  nip: props.pegawai?.nip ?? '',
  email: props.pegawai?.email ?? '',
  bidang_ajar: props.pegawai?.bidang_ajar ?? '',  
  is_active: !!props.pegawai?.is_active,           
  foto: null,
})

function handleFileChange(e) {
  form.foto = e.target.files?.[0] ?? null
}

function submit() {
  form
    .transform((data) => ({ ...data, _method: 'put' }))
    .post(route('pegawai.update', props.pegawai.id), {
      forceFormData: true,     
      preserveScroll: true,
      onError: (errors) => console.error(errors),
    })
}
</script>

<style scoped>
.form-input { border: 1px solid #cbd5e0; padding: 0.5rem; border-radius: 0.375rem; }
</style>
