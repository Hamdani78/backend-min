<template>
  <AdminLayout>
    <div class="p-6">
      <h2 class="text-xl font-bold mb-4">Tambah Akun User</h2>
      <form @submit.prevent="submit" class="space-y-4 max-w-md">
        <input v-model="form.name" placeholder="Nama" class="form-input w-full" required />
        <input v-model="form.email" type="email" placeholder="Email" class="form-input w-full" required />
        <select v-model="form.role" class="form-select w-full" required>
          <option value="pendaftar">Pendaftar</option>
          <option value="kepsek">Kepsek</option>
        </select>
        <input v-model="form.password" type="password" placeholder="Password" class="form-input w-full" required />
        <button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const form = ref({ name: '', email: '', role: '', password: '' })

function submit() {
  router.post('/admin/users', form.value)
}
</script>
