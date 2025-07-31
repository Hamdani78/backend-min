<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref, computed, onMounted } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import axios from 'axios'

const hasil = ref([])
const filterStatus = ref('all')

const page = usePage()
const flashSuccess = computed(() => page.props.flash?.success)
const flashError = computed(() => page.props.flash?.error)

const filteredHasil = computed(() => {
    if (filterStatus.value === 'all') return hasil.value
    return hasil.value.filter((s) => s.status === filterStatus.value)
})

const loadHasil = async () => {
    try {
        const res = await axios.get(route('spk.proses'))
        hasil.value = res.data

        await axios.post(route('spk.simpan'), {
            data: res.data
        })
    } catch (error) {
        console.error('Gagal memuat hasil SPK:', error)
    }
}


const cetakPDF = () => {
    window.open(route('spk.pdf'), '_blank')
}

const exportExcel = () => {
    window.location.href = route('spk.excel')
}

function applyResults() {
    if (confirm('Apakah Anda yakin ingin menerapkan hasil SPK ke database?')) {
        router.post(route('admin.spk.terapkan'), {}, {
            onFinish: () => loadHasil()
        })
    }
}

onMounted(() => {
    loadHasil()
})
</script>

<template>
    <AdminLayout title="Hasil SPK">
        <div class="p-6 bg-white rounded shadow max-w-4xl mx-auto">
            <h1 class="text-xl font-bold mb-4 text-center">Hasil Perangkingan SPK</h1>

            <!-- Flash message -->
            <div v-if="flashSuccess" class="bg-green-100 text-green-800 p-3 rounded mb-4 text-sm">
                {{ flashSuccess }}
            </div>
            <div v-if="flashError" class="bg-red-100 text-red-800 p-3 rounded mb-4 text-sm">
                {{ flashError }}
            </div>

            <!-- Tombol & Filter -->
            <div class="flex flex-wrap justify-between items-center mb-4 gap-2">
                <div class="space-x-2">
                    <button @click="cetakPDF"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-1.5 rounded text-sm">
                        Cetak PDF
                    </button>
                    <button @click="exportExcel"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-1.5 rounded text-sm">
                        Export Excel
                    </button>
                    <button @click="applyResults"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-1.5 rounded text-sm">
                        Umumkan Hasil
                    </button>
                    <button @click="router.visit(route('spk.index'))"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-1.5 rounded text-sm">
                        Kembali
                    </button>
                </div>

                <div>
                    <label class="text-sm font-medium mr-2">Filter:</label>
                    <select v-model="filterStatus"
                        class="border border-gray-300 rounded px-2 py-1 text-sm focus:ring focus:ring-blue-300">
                        <option value="all">Semua</option>
                        <option value="Lulus">Lulus</option>
                        <option value="Tidak Lulus">Tidak Lulus</option>
                    </select>
                </div>
            </div>

            <!-- Tabel -->
            <table class="min-w-full divide-y divide-gray-200 border border-gray-300 text-sm">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="border px-4 py-2">Peringkat</th>
                        <th class="border px-4 py-2">Nama</th>
                        <th class="border px-4 py-2">Nilai</th>
                        <th class="border px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(s, index) in filteredHasil" :key="index" class="hover:bg-gray-50">
                        <td class="border px-4 py-2">{{ index + 1 }}</td>
                        <td class="border px-4 py-2">{{ s.nama }}</td>
                        <td class="border px-4 py-2">{{ s.nilai }}</td>
                        <td class="border px-4 py-2">
                            <span
                                :class="s.status === 'Lulus' ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold'">
                                {{ s.status }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
