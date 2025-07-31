<template>
  <KepsekLayout>
    <div class="bg-white shadow rounded-lg overflow-hidden">
      <div class="px-6 py-4 border-b bg-green-50">
        <h1 class="text-xl font-semibold text-gray-800">Data Pegawai</h1>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-gray-700">
          <thead class="bg-gray-100 text-gray-700">
            <tr>
              <th class="px-4 py-2 cursor-pointer" @click="sortBy('nama')">
                Nama
                <span v-if="sortColumn === 'nama'">{{ sortDirection === 'asc' ? '🔼' : '🔽' }}</span>
              </th>
              <th class="px-4 py-2 cursor-pointer" @click="sortBy('nip')">
                NIP
                <span v-if="sortColumn === 'nip'">{{ sortDirection === 'asc' ? '🔼' : '🔽' }}</span>
              </th>
              <th class="px-4 py-2 cursor-pointer" @click="sortBy('status')">
                Jabatan
                <span v-if="sortColumn === 'status'">{{ sortDirection === 'asc' ? '🔼' : '🔽' }}</span>
              </th>
            </tr>
          </thead>
        </table>

        <div class="max-h-[550px] overflow-y-auto">
          <table class="min-w-full text-sm text-gray-700">
            <tbody>
              <tr
                v-for="item in sortedPegawai"
                :key="item.id"
                class="odd:bg-white even:bg-gray-50 hover:bg-green-50 transition"
              >
                <td class="px-4 py-2 border-t">{{ item.nama }}</td>
                <td class="px-4 py-2 border-t">{{ item.nip }}</td>
                <td class="px-4 py-2 border-t">{{ item.status }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </KepsekLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import KepsekLayout from '@/Layouts/KepsekLayout.vue'
const props = defineProps(['pegawai'])

const sortColumn = ref('nama')
const sortDirection = ref('asc')

const sortBy = (column) => {
  if (sortColumn.value === column) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortColumn.value = column
    sortDirection.value = 'asc'
  }
}

const sortedPegawai = computed(() => {
  return [...props.pegawai].sort((a, b) => {
    const valA = a[sortColumn.value]?.toString().toLowerCase() || ''
    const valB = b[sortColumn.value]?.toString().toLowerCase() || ''

    if (valA < valB) return sortDirection.value === 'asc' ? -1 : 1
    if (valA > valB) return sortDirection.value === 'asc' ? 1 : -1
    return 0
  })
})
</script>
