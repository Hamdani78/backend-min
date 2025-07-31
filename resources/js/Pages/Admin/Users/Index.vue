<template>
  <AdminLayout>
    <div class="p-6">
      <!-- Flash Message -->
      <div v-if="$page.props.flash?.success" class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
        {{ $page.props.flash.success }}
      </div>

      <!-- Tabel User -->
      <div class="bg-white rounded shadow p-4">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold">Data User</h3>
          <Link :href="route('users.create')">
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
              <i class="fa fa-plus mr-2"></i> Tambah
            </button>
          </Link>
        </div>

        <table class="min-w-full table-auto border text-sm">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2 border text-left">Nama</th>
              <th class="px-4 py-2 border text-left">Email</th>
              <th class="px-4 py-2 border text-left">Role</th>
              <th class="px-4 py-2 border text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50">
              <td class="px-4 py-2 border">{{ user.name }}</td>
              <td class="px-4 py-2 border">{{ user.email }}</td>
              <td class="px-4 py-2 border capitalize">{{ user.role }}</td>
              <td class="px-4 py-2 border">
                <div class="flex justify-center gap-2">
                  <Link :href="`/admin/users/${user.id}/edit`" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded">
                    <i class="fa fa-edit"></i>
                  </Link>
                  <button @click="hapus(user.id)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                    <i class="fa fa-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { defineProps } from 'vue'

const props = defineProps({
  users: Array
})

function hapus(id) {
  if (confirm('Hapus user ini?')) {
    router.delete(`/admin/users/${id}`)
  }
}
</script>
