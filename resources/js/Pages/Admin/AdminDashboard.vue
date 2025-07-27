<script setup>
import { ref } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Lightbox from 'vue-easy-lightbox'
import StatCard from '@/Components/StatCard.vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import 'swiper/css'

defineOptions({ layout: AdminLayout })

const props = defineProps({
  totalPegawai: Number,
  totalSiswa: Number,
  totalKegiatan: Number,
  totalFasilitas: Number,
  fasilitas: Array,
  fasilitasImages: Array,
  kegiatan: Array,
  kegiatanImages: Array
})

const showLightbox = ref(false)
const lightboxImages = ref([])
const lightboxIndex = ref(0)

function openLightbox(images, index = 0) {
  lightboxImages.value = images
  lightboxIndex.value = index
  showLightbox.value = true
}

function filteredFasilitasImages(id) {
  return props.fasilitasImages.filter(img => img.fasilitas_id === id)
}

function filteredKegiatanImages(id) {
  return props.kegiatanImages.filter(img => img.kegiatans_id === id)
}
</script>

<template>
  <div class="space-y-8">
    <!-- Statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
      <StatCard icon="fas fa-user-tie" label="Total Pegawai" :value="totalPegawai" bgColor="bg-blue-500" />
      <StatCard icon="fas fa-user-graduate" label="Total Siswa" :value="totalSiswa" bgColor="bg-green-600" />
      <StatCard icon="fas fa-school" label="Total Fasilitas" :value="totalFasilitas" bgColor="bg-cyan-400" />
      <StatCard icon="fas fa-calendar-alt" label="Total Kegiatan" :value="totalKegiatan" bgColor="bg-yellow-400" />
    </div>

    <!-- Fasilitas -->
    <div class="bg-blue-600 text-white text-xl font-bold rounded shadow p-4 text-center">
      Fasilitas Madrasah
    </div>
    <div class="grid gap-6">
      <div v-for="item in fasilitas" :key="item.id" class="bg-white rounded shadow p-4">
        <h2 class="text-xl font-semibold mb-3">{{ item.nama }}</h2>
        <template v-if="filteredFasilitasImages(item.id).length">
          <div class="overflow-hidden">
            <Swiper :slides-per-view="'auto'" :space-between="10" class="!w-full">
              <SwiperSlide v-for="(img, index) in filteredFasilitasImages(item.id)" :key="img.id" class="!w-36">
                <img :src="`/${img.foto}`" class="w-full h-32 object-cover rounded cursor-pointer"
                  @click="openLightbox(filteredFasilitasImages(item.id).map(i => `/${i.foto}`), index)" />
              </SwiperSlide>
            </Swiper>
          </div>
        </template>
        <p v-else class="text-gray-500">Tidak ada foto tersedia untuk fasilitas ini.</p>
      </div>
    </div>

    <!-- Kegiatan -->
    <div class="bg-blue-600 text-white text-xl font-bold rounded shadow p-4 text-center">
      Kegiatan Madrasah
    </div>
    <div class="grid gap-6">
      <div v-for="item in kegiatan" :key="item.id" class="bg-white rounded shadow p-4">
        <h2 class="text-xl font-semibold mb-3">{{ item.nama }}</h2>
        <template v-if="filteredKegiatanImages(item.id).length">
          <div class="overflow-hidden">
            <Swiper :slides-per-view="'auto'" :space-between="10" class="!w-full">
              <SwiperSlide v-for="(img, index) in filteredKegiatanImages(item.id)" :key="img.id" class="!w-36">
                <img :src="`/uploads/kegiatan/${img.foto}`" class="w-full h-32 object-cover rounded cursor-pointer"
                  @click="openLightbox(filteredKegiatanImages(item.id).map(i => `/uploads/kegiatan/${i.foto}`), index)" />
              </SwiperSlide>
            </Swiper>
          </div>
        </template>
        <p v-else class="text-gray-500">Tidak ada foto tersedia untuk kegiatan ini.</p>
      </div>
    </div>

    <!-- Lightbox -->
    <Lightbox :imgs="lightboxImages" :index="lightboxIndex" :visible="showLightbox" @hide="showLightbox = false" />
  </div>
</template>
