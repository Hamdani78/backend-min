<template>
  <div>
    <!-- Navbar -->
    <div class="container position-sticky z-index-sticky top-0">
      <div class="row">
        <div class="col-12">
          <DefaultNavbar :sticky="true" />
        </div>
      </div>
    </div>

    <!-- Header Section -->
    <section class="kgt-scope pt-3 pb-1 mt-7">
      <div class="container">
        <div class="row justify-content-center text-center my-sm-3">
          <div class="col-lg-6">
            <h2 class="text-dark font-weight-bold">Kegiatan Madrasah</h2>
            <p class="lead text-secondary">Berikut adalah dokumentasi kegiatan yang dilakukan di madrasah kami.</p>
          </div>
        </div>

        <!-- Swiper: 1 slide = 1 card besar yang membungkus 6 card kecil -->
        <Swiper
          :modules="[Navigation, Pagination]"
          navigation
          :pagination="{ clickable: true }"
          :slides-per-view="1"
          :space-between="24"
          :observer="true"
          :observe-parents="true"
          class="kgt-swiper"
        >
          <SwiperSlide v-for="(group, idx) in chunkArray(kegiatan, 6)" :key="idx">
            <!-- Card besar (board) -->
            <div class="card shadow-sm kgt-board">
              <div class="card-header bg-white border-0 pb-2 d-flex justify-content-between align-items-center">
                <span class="text-secondary">Halaman {{ idx + 1 }}</span>
              </div>
              <div class="card-body pt-0">
                <!-- Grid 6 item di dalam board | pakai struktur card kecil yang sudah ada -->
                <div class="row g-3 g-lg-4">
                  <div v-for="item in group" :key="item.id" class="col-12 col-md-6 col-lg-4">
                    <div class="card shadow-lg h-100">
                      <!-- Carousel per kegiatan (tidak diubah) -->
                      <div
                        v-if="item.images && item.images.length > 0"
                        :id="`carousel-kegiatan-${item.id}`"
                        class="carousel slide"
                        data-bs-ride="carousel"
                      >
                        <div class="carousel-inner rounded shadow">
                          <div
                            v-for="(image, index) in item.images"
                            :key="image.id"
                            :class="['carousel-item', { active: index === 0 }]"
                          >
                            <img
                              :src="normalizeSrc(image.foto)"
                              class="d-block w-100"
                              style="object-fit: cover; height: 250px;"
                              alt="Foto kegiatan"
                              loading="lazy"
                            />
                          </div>
                        </div>
                        <button
                          class="carousel-control-prev"
                          type="button"
                          :data-bs-target="`#carousel-kegiatan-${item.id}`"
                          data-bs-slide="prev"
                        >
                          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                          <span class="visually-hidden">Previous</span>
                        </button>
                        <button
                          class="carousel-control-next"
                          type="button"
                          :data-bs-target="`#carousel-kegiatan-${item.id}`"
                          data-bs-slide="next"
                        >
                          <span class="carousel-control-next-icon" aria-hidden="true"></span>
                          <span class="visually-hidden">Next</span>
                        </button>
                      </div>

                      <!-- Deskripsi (tetap) -->
                      <div class="card-body">
                        <h5 class="card-title text-dark">{{ item.nama }}</h5>
                        <p class="card-text text-secondary" v-if="item.deskripsi">
                          {{ item.deskripsi }}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div> 
            </div> 
          </SwiperSlide>
        </Swiper>
      </div>
    </section>

    <FooterCentered />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import DefaultNavbar from "../../../examples/navbars/NavbarDefault.vue"
import FooterCentered from "../../../examples/footers/FooterCentered.vue"

import { Swiper, SwiperSlide } from 'swiper/vue'
import { Navigation, Pagination } from 'swiper/modules'
import 'swiper/css'
import 'swiper/css/navigation'
import 'swiper/css/pagination'

const kegiatan = ref([])

onMounted(async () => {
  try {
    const response = await axios.get('/landing/kegiatan')
    kegiatan.value = response.data ?? []
  } catch (err) {
    console.error('Gagal memuat kegiatan:', err)
  }
})

function chunkArray(arr, size) {
  if (!Array.isArray(arr)) return []
  return Array.from({ length: Math.ceil(arr.length / size) }, (_, i) =>
    arr.slice(i * size, i * size + size)
  )
}

// pastikan path gambar valid (leading slash)
function normalizeSrc(src) {
  if (!src) return '/images/no-image.png'
  return src.startsWith('http') || src.startsWith('/') ? src : `/${src}`
}
</script>

<style scoped>
.card-title { font-weight: 600; }

/* ==== Board besar dan grid ==== */
.kgt-board { border-radius: 16px; }
.kgt-board .card-header { border-bottom: 1px dashed #e5e7eb !important; }

/* ==== Swiper height fix: slide mengikuti tinggi konten ==== */
:deep(.kgt-swiper .swiper-slide){ height: auto; }
:deep(.kgt-swiper .swiper-wrapper){ align-items: stretch; }

/* ==== Rapikan pagination & arrows ==== */
:deep(.kgt-swiper .swiper-pagination){
  bottom: 6px;
  margin-bottom: 0;
}
:deep(.kgt-swiper .swiper-pagination-bullet){ background:#c7d2fe; opacity:1; }
:deep(.kgt-swiper .swiper-pagination-bullet-active){ background:#4f46e5; }

:deep(.kgt-swiper .swiper-button-prev),
:deep(.kgt-swiper .swiper-button-next){
  color:#2563eb; width:44px; height:44px; background:#eff6ff; border-radius:9999px;
  box-shadow:0 4px 12px rgba(37,99,235,.15);
}
:deep(.kgt-swiper .swiper-button-prev:after),
:deep(.kgt-swiper .swiper-button-next:after){ font-size:16px; }
</style>
