<template>
  <KepsekLayout>
    <div class="bg-white shadow rounded-lg p-6">
      <h1 class="text-xl font-semibold mb-4">Data Pendaftar dan Nilai SPK</h1>

      <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-gray-700">
          <thead class="bg-gray-100 text-left">
            <tr>
              <th class="px-4 py-2">Nama</th>
              <th class="px-4 py-2">Status Lulus</th>
              <th class="px-4 py-2">Nilai SPK</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in pendaftar" :key="item.id"
                class="odd:bg-white even:bg-gray-50 hover:bg-green-50 transition">
              <td class="px-4 py-2 border-t">{{ item.nama }}</td>
              <td class="px-4 py-2 border-t">
                <span :class="statusClass(item.status_lulus)">
                  {{ item.status_lulus || '-' }}
                </span>
              </td>
              <td class="px-4 py-2 border-t">{{ item.nilai_spk ?? '-' }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </KepsekLayout>
</template>

<script setup>
import KepsekLayout from '@/Layouts/KepsekLayout.vue'
const props = defineProps(['pendaftar'])

function statusClass(status) {
  return status === 'lulus'
    ? 'text-green-600 font-semibold'
    : status === 'tidak lulus'
    ? 'text-red-600 font-semibold'
    : 'text-gray-500'
}
</script>
