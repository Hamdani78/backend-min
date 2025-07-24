<template>
  <AdminLayout :title="`Galeri Foto - ${kegiatan.nama}`">
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex justify-between items-center">
        <div>
          <h2 class="text-xl font-bold">Kegiatan: {{ kegiatan.nama }}</h2>
          <p>ID: {{ kegiatan.id }}</p>
        </div>
        <Link :href="route('kegiatan.index')" class="btn btn-primary">Back</Link>
      </div>

      <!-- Flash Message -->
      <div v-if="$page.props.flash?.status" class="bg-green-100 text-green-700 px-4 py-2 rounded">
        {{ $page.props.flash.status }}
      </div>
      <div v-if="$page.props.flash?.error" class="bg-red-100 text-red-700 px-4 py-2 rounded">
        {{ $page.props.flash.error }}
      </div>

      <!-- Upload Form -->
      <form @submit.prevent="submit">
        <label class="block font-medium mb-1">Upload Foto (maks: 20)</label>
        <input
          type="file"
          accept="image/*"
          multiple
          @change="handleFileChange"
          class="border border-gray-300 p-2 rounded w-full"
        />
        <button
          type="submit"
          class="mt-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded disabled:opacity-50"
          :disabled="uploading"
        >
          {{ uploading ? 'Mengupload...' : 'Upload Foto' }}
        </button>
      </form>

      <!-- Galeri Slide -->
      <Swiper
        :modules="[Navigation, Pagination]"
        :slides-per-view="1"
        :space-between="30"
        navigation
        pagination
        class="mt-6 w-full max-w-4xl mx-auto rounded shadow"
      >
        <SwiperSlide v-for="img in images" :key="img.id">
          <div class="relative">
            <a :href="'/' + img.foto" target="_blank">
              <img :src="'/' + img.foto" class="w-full max-h-[500px] object-contain rounded" />
            </a>
            <button
              @click="hapus(img.id)"
              class="absolute top-4 right-4 bg-red-600 text-white px-3 py-1 text-sm rounded hover:bg-red-700"
            >
              Hapus
            </button>
          </div>
        </SwiperSlide>
      </Swiper>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Navigation, Pagination } from 'swiper/modules'
import 'swiper/css'
import 'swiper/css/navigation'
import 'swiper/css/pagination'

import { ref } from 'vue'
import { router, Link } from '@inertiajs/vue3'

const props = defineProps({
  kegiatan: Object,
  images: Array,
})

const files = ref([])
const uploading = ref(false)

function handleFileChange(e) {
  const selected = Array.from(e.target.files)

  // Filter hanya gambar
  const imageFiles = selected.filter(file => file.type.startsWith('image/'))

  // Maksimal 20 file
  if (imageFiles.length > 20) {
    alert('Maksimal 20 gambar yang diizinkan')
    return
  }

  files.value = imageFiles
}

function submit() {
  if (files.value.length === 0) return alert('Pilih gambar terlebih dahulu')

  const formData = new FormData()
  files.value.forEach((file) => formData.append('foto[]', file))

  uploading.value = true
  router.post(`/admin/kegiatan/${props.kegiatan.id}/images`, formData, {
    forceFormData: true,
    preserveScroll: true,
    onFinish: () => {
      uploading.value = false
      files.value = []
    },
  })
}

function hapus(id) {
  if (!confirm('Yakin hapus gambar ini?')) return
  router.delete(route('kegiatanimage.destroy', id), {
    preserveScroll: true,
  })
}
</script>
