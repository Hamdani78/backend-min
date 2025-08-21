<template>
  <AdminLayout title="Pengaturan PPDB">
    <div class="max-w-2xl mx-auto">
      <!-- Flash -->
      <div
        v-if="$page.props.flash?.success"
        class="mb-4 rounded border border-green-200 bg-green-50 px-4 py-2 text-green-700"
      >
        {{ $page.props.flash.success }}
      </div>

      <!-- Status ringkas -->
      <div class="mb-4 rounded border border-gray-200 bg-gray-50 p-4">
        <div class="flex items-center justify-between">
          <div class="font-medium">Status Efektif</div>
          <span
            class="rounded-full px-3 py-0.5 text-xs text-white"
            :class="effectiveOpen ? 'bg-green-600' : 'bg-red-600'"
          >
            {{ effectiveOpen ? 'Buka' : 'Tutup' }}
          </span>
        </div>
        <p class="mt-1 text-sm text-gray-600">
          {{ statusText }}
        </p>
      </div>

      <!-- Form -->
      <form @submit.prevent="submit" class="rounded bg-white p-6 shadow">
        <!-- Status Manual -->
        <div class="mb-4">
          <label class="font-medium">Status Manual</label>
          <select v-model="form.ppdb_open" class="mt-1 w-full rounded border px-3 py-2">
            <option value="0">Tutup</option>
            <option value="1">Buka</option>
            <option value="auto">Otomatis (berdasarkan tanggal)</option>
          </select>
          <p class="mt-1 text-xs text-gray-500">
            Jika “Buka” atau “Tutup”, sistem mengabaikan tanggal. Mode “Otomatis” mengikuti periode di bawah.
          </p>
        </div>

        <!-- Periode tanggal -->
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
          <div>
            <label class="font-medium">Tanggal Buka</label>
            <input
              type="date"
              v-model="form.ppdb_open_at"
              class="mt-1 w-full rounded border px-3 py-2"
              :disabled="form.ppdb_open !== 'auto'"
            />
            <p v-if="form.errors.ppdb_open_at" class="mt-1 text-xs text-red-600">
              {{ form.errors.ppdb_open_at }}
            </p>
          </div>
          <div>
            <label class="font-medium">Tanggal Tutup</label>
            <input
              type="date"
              v-model="form.ppdb_close_at"
              class="mt-1 w-full rounded border px-3 py-2"
              :min="form.ppdb_open_at || undefined"
              :disabled="form.ppdb_open !== 'auto'"
            />
            <p v-if="form.errors.ppdb_close_at" class="mt-1 text-xs text-red-600">
              {{ form.errors.ppdb_close_at }}
            </p>
          </div>
        </div>

        <!-- Banner -->
        <div class="mt-4">
          <label class="font-medium">Pesan Banner</label>
          <input
            type="text"
            v-model="form.ppdb_banner"
            class="mt-1 w-full rounded border px-3 py-2"
            placeholder="PPDB saat ini ditutup."
          />
          <p v-if="form.errors.ppdb_banner" class="mt-1 text-xs text-red-600">
            {{ form.errors.ppdb_banner }}
          </p>
        </div>

        <!-- Actions -->
        <div class="mt-6 flex gap-2">
          <button
            class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 disabled:opacity-60"
            :disabled="form.processing"
          >
            {{ form.processing ? 'Menyimpan…' : 'Simpan' }}
          </button>
          <button
            type="button"
            class="rounded border px-4 py-2 hover:bg-gray-50"
            @click="resetForm"
            :disabled="form.processing"
          >
            Reset
          </button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  ppdb_open:    { type: String, default: '0' },     // '0' | '1' | 'auto'
  ppdb_open_at: { type: String, default: null },
  ppdb_close_at:{ type: String, default: null },
  ppdb_banner:  { type: String, default: 'PPDB saat ini ditutup.' },
})

const form = useForm({
  ppdb_open:    props.ppdb_open ?? '0',
  ppdb_open_at: props.ppdb_open_at ?? null,
  ppdb_close_at:props.ppdb_close_at ?? null,
  ppdb_banner:  props.ppdb_banner ?? 'PPDB saat ini ditutup.',
})

const nowBetween = (start, end) => {
  if (!start || !end) return false
  const n = new Date()
  const s = new Date(start + 'T00:00:00')
  const e = new Date(end + 'T23:59:59')
  return n >= s && n <= e
}

const effectiveOpen = computed(() => {
  if (form.ppdb_open === '1')   return true   // manual buka
  if (form.ppdb_open === '0')   return false  // manual tutup
  if (form.ppdb_open === 'auto') {
    return nowBetween(form.ppdb_open_at, form.ppdb_close_at)
  }
  return false
})

const statusText = computed(() => {
  if (form.ppdb_open === '1')   return 'Dibuka secara manual oleh admin.'
  if (form.ppdb_open === '0')   return 'Ditutup secara manual oleh admin.'
  if (form.ppdb_open === 'auto') {
    return nowBetween(form.ppdb_open_at, form.ppdb_close_at)
      ? `Dibuka berdasarkan periode ${form.ppdb_open_at} s.d. ${form.ppdb_close_at}.`
      : `Di luar periode ${form.ppdb_open_at} s.d. ${form.ppdb_close_at}.`
  }
  return 'Tidak ada periode tanggal—status default: Tutup.'
})

const submit = () => form.post(route('admin.settings.ppdb.update'), { preserveScroll: true })
const resetForm = () => form.reset('ppdb_open','ppdb_open_at','ppdb_close_at','ppdb_banner')
</script>
