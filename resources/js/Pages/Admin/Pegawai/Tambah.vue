<template>
  <AdminLayout>
    <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
      <h2 class="text-xl font-bold mb-4">Tambah Data Pegawai</h2>

      <form @submit.prevent="submit" enctype="multipart/form-data">
        <!-- Nama -->
        <div class="mb-4">
          <label for="nama" class="block font-medium">Nama</label>
          <input v-model="form.nama" type="text" id="nama" class="form-input w-full" placeholder="Masukkan Nama" />
          <div v-if="form.errors.nama" class="text-red-500 text-sm mt-1">{{ form.errors.nama }}</div>
        </div>

        <!-- NIP -->
        <div class="mb-4">
          <label for="nip" class="block font-medium">NIP</label>
          <input v-model="form.nip" type="text" id="nip" class="form-input w-full" placeholder="Masukkan NIP" />
          <div v-if="form.errors.nip" class="text-red-500 text-sm mt-1">{{ form.errors.nip }}</div>
        </div>

        <!-- Email -->
        <div class="mb-4">
          <label for="email" class="block font-medium">Email</label>
          <input v-model="form.email" type="email" id="email" class="form-input w-full" placeholder="Masukkan Email" />
          <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</div>
        </div>

        <!-- Bidang Ajar-->
        <div class="mb-4">
          <label for="bidang_ajar" class="block font-medium">Bidang Ajar</label>
          <input v-model="form.bidang_ajar" type="text" id="bidang_ajar" class="form-input w-full"
            placeholder="Contoh: Guru Kelas IV.B" />
          <div v-if="form.errors.bidang_ajar" class="text-red-500 text-sm mt-1">{{ form.errors.bidang_ajar }}</div>
        </div>

        <!-- Keaktifan (boolean) -->
        <div class="mb-4">
          <label class="block font-medium">Status</label>
          <select v-model="form.is_active" class="form-input w-full mt-1">
            <option :value="true">Aktif</option>
            <option :value="false">Non-aktif</option>
          </select>
          <div v-if="form.errors.is_active" class="text-red-500 text-sm mt-1">{{ form.errors.is_active }}</div>
        </div>

        <!-- Foto -->
        <div class="mb-4">
          <label for="foto" class="block font-medium">Foto</label>
          <input @change="handleFileChange" type="file" id="foto" accept="image/*" class="form-input w-full" />
          <div v-if="form.errors.foto" class="text-red-500 text-sm mt-1">{{ form.errors.foto }}</div>
        </div>

        <!-- Submit -->
        <div class="mt-6">
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
            :disabled="form.processing">
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
  bidang_ajar: '',  
  is_active: true,   
  foto: null
})

function handleFileChange(e) {
  form.foto = e.target.files?.[0] ?? null
}

function submit() {
  form.post(route('pegawai.store'), { preserveScroll: true })
}
</script>

<style scoped>
.form-input {
  border: 1px solid #cbd5e0;
  padding: 0.5rem;
  border-radius: 0.375rem;
}
</style>
