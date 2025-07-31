<template>
  <AdminLayout>
    <div class="p-6 max-w-md mx-auto bg-white shadow rounded">
      <h2 class="text-xl font-bold mb-4">Edit Akun User</h2>

      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block font-medium mb-1">Nama</label>
          <input v-model="form.name" type="text" class="form-input w-full" required />
        </div>

        <div>
          <label class="block font-medium mb-1">Email</label>
          <input v-model="form.email" type="email" class="form-input w-full" required />
        </div>

        <div>
          <label class="block font-medium mb-1">Role</label>
          <select v-model="form.role" class="form-select w-full" required>
            <option value="pendaftar">Pendaftar</option>
            <option value="kepsek">Kepsek</option>
          </select>
        </div>

        <div>
          <label class="block font-medium mb-1">Password (Kosongkan jika tidak diubah)</label>
          <input v-model="form.password" type="password" class="form-input w-full" />
        </div>

        <div class="flex justify-end">
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  user: Object,
})

const form = useForm({
  name: props.user.name,
  email: props.user.email,
  role: props.user.role,
  password: ''
})

function submit() {
  form.put(`/admin/users/${props.user.id}`)
}
</script>
