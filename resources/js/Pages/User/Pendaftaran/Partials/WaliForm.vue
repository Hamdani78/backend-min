<script setup>
import { useForm } from '@inertiajs/vue3'
import { watch } from 'vue'

const props = defineProps({
  initial: { type: Object, default: null },     // objek wali: { nama, hubungan_keluarga }
  hasPendaftar: { type: Boolean, default: false },
  isLocked: { type: Boolean, default: false },  // kunci UI jika sudah diverifikasi
  verificationNote: { type: String, default: null },
})

// form state (cocok dgn kolom tabel)
const form = useForm({
  nama: props.initial?.nama ?? '',
  hubungan_keluarga: props.initial?.hubungan_keluarga ?? '',
})

// rehydrate ketika props.initial berubah
watch(() => props.initial, (v) => {
  form.defaults({
    nama: v?.nama ?? '',
    hubungan_keluarga: v?.hubungan_keluarga ?? '',
  })
  form.reset()
}, { immediate: true })

function submit() {
  if (props.isLocked) return
  if (!props.hasPendaftar) return alert('Harap simpan Data Diri terlebih dahulu.')

  form.transform((d) => {
    const out = { ...d }
    for (const k of Object.keys(out)) {
      if (typeof out[k] === 'string') {
        out[k] = out[k].trim()
        if (out[k] === '') out[k] = null
      }
    }
    return out
  })

  form.post(route('user.pendaftaran.wali.upsert'), {
    preserveScroll: true,
    onSuccess: () => form.clearErrors(),
  })
}
</script>

<template>
  <div class="bg-white rounded shadow p-6">
    <!-- Banner status verifikasi -->
    <div v-if="isLocked" class="mb-4 p-3 rounded border border-green-200 bg-green-50 text-green-800 text-sm">
      ✔ Data sudah <strong>diverifikasi admin</strong> dan tidak bisa diedit.
    </div>
    <div v-if="verificationNote" class="mb-4 p-3 rounded border border-yellow-200 bg-yellow-50 text-yellow-800 text-sm">
      <strong>Catatan dari admin:</strong>
      <div class="mt-1 whitespace-pre-line">{{ verificationNote }}</div>
    </div>

    <h2 class="text-lg font-semibold mb-4">Data Wali</h2>

    <!-- Mass disable jika terkunci -->
    <fieldset :disabled="isLocked">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <div class="flex justify-between items-center">
            <label class="block text-sm font-medium">Nama Wali</label>
          </div>
          <input v-model="form.nama" class="form-input w-full mt-1 border rounded p-2" />
          <p v-if="form.errors.nama" class="text-red-600 text-sm mt-1">{{ form.errors.nama }}</p>
        </div>

        <div>
          <div class="flex justify-between items-center">
            <label class="block text-sm font-medium">Hubungan Keluarga</label>
            <span class="text-xs text-gray-500">Contoh: Paman / Bibi / Kakak</span>
          </div>
          <input v-model="form.hubungan_keluarga" class="form-input w-full mt-1 border rounded p-2" />
          <p v-if="form.errors.hubungan_keluarga" class="text-red-600 text-sm mt-1">{{ form.errors.hubungan_keluarga }}</p>
        </div>
      </div>
    </fieldset>

    <div class="mt-6">
      <button
        v-if="!isLocked"
        @click="submit"
        :disabled="form.processing || !hasPendaftar"
        class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700 disabled:opacity-50"
      >
        {{ form.processing ? 'Menyimpan...' : 'Simpan / Update Wali' }}
      </button>
    </div>
  </div>
</template>
