<template>
    <AdminLayout>
        <div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">
            <h2 class="text-xl font-bold mb-4">Detail Pendaftar</h2>

            <!-- 🔹 Data Siswa -->
            <section class="mb-6">
                <h3 class="font-semibold mb-2 text-gray-700">Data Siswa</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <p><strong>Nama:</strong> {{ pendaftar.nama }}</p>
                    <p><strong>Tempat, Tgl Lahir:</strong> {{ pendaftar.tempat_lahir }}, {{ pendaftar.tanggal_lahir }}
                    </p>
                    <p><strong>Jenis Kelamin:</strong> {{ jenisKelamin }}</p>
                    <p><strong>Agama:</strong> {{ pendaftar.agama || '-' }}</p>
                    <p><strong>Bahasa:</strong> {{ pendaftar.bahasa || '-' }}</p>
                    <p><strong>Cita-cita:</strong> {{ pendaftar.cita_cita || '-' }}</p>
                </div>
                <div v-if="pendaftar.foto" class="mt-4">
                    <p class="font-semibold">Foto:</p>
                    <img :src="`/storage/${pendaftar.foto}`" class="w-32 rounded border mt-2" />
                </div>
            </section>

            <!-- 🔹 Data Ayah & Ibu -->
            <section class="mb-6">
                <h3 class="font-semibold mb-2 text-gray-700">Data Orang Tua</h3>

                <div v-for="(ortu, index) in orangTuas" :key="index" class="border p-4 mb-4 rounded bg-gray-50">
                    <h4 class="font-bold mb-2">{{ ortu.tipe }}</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <p><strong>Nama:</strong> {{ ortu.nama }}</p>
                        <p><strong>Status:</strong> {{ ortu.status }}</p>
                        <p><strong>NIK:</strong> {{ ortu.nik }}</p>
                        <p><strong>Tempat, Tgl Lahir:</strong> {{ ortu.tempat_lahir }}, {{ ortu.tanggal_lahir }}</p>
                        <p><strong>Pendidikan:</strong> {{ ortu.pendidikan }}</p>
                        <p><strong>Pekerjaan:</strong> {{ ortu.pekerjaan }}</p>
                        <p><strong>Penghasilan:</strong> {{ ortu.penghasilan }}</p>
                        <p><strong>No HP:</strong> {{ ortu.no_hp }}</p>
                    </div>
                </div>
            </section>

            <!-- 🔹 Data Wali -->
            <section v-if="pendaftar.wali" class="mb-6">
                <h3 class="font-semibold mb-2 text-gray-700">Data Wali</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <p><strong>Nama:</strong> {{ pendaftar.wali.nama }}</p>
                    <p><strong>Hubungan:</strong> {{ pendaftar.wali.hubungan_keluarga }}</p>
                </div>
            </section>

            <button @click="kembali" type="button"
                class="bg-gray-200 hover:bg-gray-300 text-sm text-gray-800 px-4 py-2 rounded">
                &larr; Kembali ke daftar
            </button>

        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    pendaftar: Object,
})

function kembali() {
  router.visit(route('pendaftar.index'))
}

const orangTuas = props.pendaftar.orang_tuas || []

const jenisKelamin = props.pendaftar.jenis_kelamin === 'L' ? 'Laki-laki'
    : props.pendaftar.jenis_kelamin === 'P' ? 'Perempuan'
        : '-'
</script>
