<script setup>
import { ref, computed, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
import UserLayout from '../UserLayouts/UserLayout.vue'

import DataDiriForm from './Partials/DataDiriForm.vue'
import OrangTuaForm from './Partials/OrangTuaForm.vue'
import WaliForm from './Partials/WaliForm.vue'

const page = usePage()
const props = defineProps({
  pendaftar: { type: Object, default: null },
  ayah: { type: Object, default: null },
  ibu: { type: Object, default: null },
  wali: { type: Object, default: null },
})

// status verifikasi untuk anak
const isLocked = computed(() => !!props.pendaftar?.is_verified)
const verificationNote = computed(() => props.pendaftar?.verification_note ?? null)

// ---- Helper: ambil ?tab= dari URL (prioritas)
function getQueryTab() {
  const raw = typeof page.url === 'string' ? page.url : window.location.href
  try {
    const url = new URL(raw, 'https://dummy.local')
    return url.searchParams.get('tab')
  } catch {
    return null
  }
}

// ---- Fallback tab awal berdasarkan progres data
const fallbackInitialTab = computed(() => {
  if (!props.pendaftar) return 'data-diri'
  if (!props.ayah || !props.ibu) return 'orang-tua'
  if (!props.wali) return 'wali'
  return 'data-diri'
})

// ---- State tab aktif
const tab = ref(getQueryTab() || fallbackInitialTab.value)

// ---- Guard akses tab berikutnya
const canOpenOrangTua = computed(() => !!props.pendaftar)
const canOpenWali = computed(() => !!props.pendaftar)

// ---- Saat tab berubah, update query ?tab= (tanpa request ulang)
watch(tab, (t) => {
  const url = route('user.pendaftaran.create', { tab: t })
  window.history.replaceState({}, '', url)
})

// ---- Handler klik tab dengan guard
function go(next) {
  if (next === 'orang-tua' && !canOpenOrangTua.value) {
    tab.value = 'data-diri'
    return
  }
  if (next === 'wali' && !canOpenWali.value) {
    tab.value = 'data-diri'
    return
  }
  tab.value = next
}
</script>

<template>
  <UserLayout>
    <div class="max-w-5xl mx-auto py-8">
      <!-- Banner status verifikasi -->
      <div v-if="isLocked" class="mb-4 rounded border border-green-200 bg-green-50 text-green-800 px-4 py-2">
        ✔ Data sudah <strong>diverifikasi admin</strong> dan tidak bisa diedit.
      </div>
      <div v-if="verificationNote" class="mb-4 rounded border border-yellow-200 bg-yellow-50 text-yellow-800 px-4 py-2">
        <strong>Catatan dari admin:</strong>
        <div class="mt-1 whitespace-pre-line">{{ verificationNote }}</div>
      </div>

      <!-- Flash -->
      <div v-if="page.props.flash?.success" class="mb-4 rounded border border-green-200 bg-green-50 text-green-800 px-4 py-2">
        {{ page.props.flash.success }}
      </div>
      <div v-if="page.props.flash?.error" class="mb-4 rounded border border-red-200 bg-red-50 text-red-800 px-4 py-2">
        {{ page.props.flash.error }}
      </div>

      <!-- Tabs -->
      <div class="flex gap-2 border-b mb-6">
        <button
          class="px-4 py-2"
          :class="tab==='data-diri' ? 'border-b-2 border-green-600 font-semibold' : ''"
          @click="go('data-diri')">
          Data Diri
        </button>

        <button
          class="px-4 py-2 disabled:opacity-50"
          :disabled="!canOpenOrangTua"
          :title="!canOpenOrangTua ? 'Isi & simpan Data Diri dulu' : ''"
          :class="tab==='orang-tua' ? 'border-b-2 border-green-600 font-semibold' : ''"
          @click="go('orang-tua')">
          Orang Tua
        </button>

        <button
          class="px-4 py-2 disabled:opacity-50"
          :disabled="!canOpenWali"
          :title="!canOpenWali ? 'Isi & simpan Data Diri dulu' : ''"
          :class="tab==='wali' ? 'border-b-2 border-green-600 font-semibold' : ''"
          @click="go('wali')">
          Wali
        </button>
      </div>

      <!-- Panels -->
      <div v-show="tab==='data-diri'">
        <DataDiriForm
          :initial="props.pendaftar"
          :isLocked="isLocked"
          :verificationNote="verificationNote"
        />
      </div>

      <div v-show="tab==='orang-tua'">
        <OrangTuaForm
          :ayah="props.ayah"
          :ibu="props.ibu"
          :hasPendaftar="!!props.pendaftar"
          :isLocked="isLocked"
          :verificationNote="verificationNote"
        />
      </div>

      <div v-show="tab==='wali'">
        <WaliForm
          :initial="props.wali"
          :hasPendaftar="!!props.pendaftar"
          :isLocked="isLocked"
          :verificationNote="verificationNote"
        />
      </div>
    </div>
  </UserLayout>
</template>
