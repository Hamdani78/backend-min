<template>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center p-6">

        <Head title="PPDB Ditutup" />

        <div class="mx-auto max-w-2xl rounded bg-white p-6 text-center shadow">
            <h1 class="mb-2 text-2xl font-bold">PPDB Ditutup</h1>

            <p class="mb-3 text-gray-700">
                {{ message || 'Pendaftaran belum dibuka atau sudah ditutup.' }}
            </p>

            <div v-if="open_at || close_at" class="text-sm text-gray-600">
                <div v-if="open_at" class="mb-0.5">
                    Rencana buka: <strong>{{ fmt(open_at) }}</strong>
                </div>
                <div v-if="close_at">
                    Rencana tutup: <strong>{{ fmt(close_at) }}</strong>
                </div>
            </div>

            <div v-if="countdownText" class="mt-3 text-sm text-indigo-700">
                {{ countdownText }}
            </div>

            <!-- <div class="mt-6">
                <Link href="/min/pendaftaran-peserta-didik-baru" class="rounded bg-gray-900 px-4 py-2 text-white hover:bg-gray-800">
                Kembali ke Beranda
                </Link>
            </div> -->
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import { Link, Head } from '@inertiajs/vue3'

const props = defineProps({
    message: String,
    open_at: String,   // 'YYYY-MM-DD'
    close_at: String,  // 'YYYY-MM-DD'
})

// Format tanggal Indonesia
const fmt = (d) => {
    try {
        return new Date(d).toLocaleDateString('id-ID', {
            day: '2-digit', month: 'long', year: 'numeric'
        })
    } catch { return d }
}

// Hitung mundur sederhana menuju tanggal buka
const countdownText = computed(() => {
    if (!props.open_at) return ''
    const open = new Date(props.open_at + 'T00:00:00')
    const now = new Date()
    const diff = open - now
    if (diff <= 0) return ''
    const days = Math.ceil(diff / (1000 * 60 * 60 * 24))
    return `Dibuka sekitar ${days} hari lagi.`
})
</script>
