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
    <section class="my-5 py-5">
      <div class="container">
        <div class="row justify-content-center text-center my-sm-5">
          <div class="col-lg-6">
            <h2 class="text-dark font-weight-bold">Kegiatan Madrasah</h2>
            <p class="lead text-secondary">Berikut adalah dokumentasi kegiatan yang dilakukan di madrasah kami.</p>
          </div>
        </div>

        <!-- Card List -->
        <div class="row">
          <div v-for="item in kegiatan" :key="item.id" class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card shadow-lg h-100">
              <!-- Carousel jika ada gambar -->
              <div v-if="item.images && item.images.length > 0" :id="`carousel-kegiatan-${item.id}`"
                   class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner rounded shadow">
                  <div v-for="(image, index) in item.images" :key="image.id"
                       :class="['carousel-item', { active: index === 0 }]">
                    <img
                      :src="`/${image.foto}`"
                      class="d-block w-100"
                      style="object-fit: cover; height: 250px;"
                      alt="Foto kegiatan"
                    />
                  </div>
                </div>
                <button class="carousel-control-prev" type="button" :data-bs-target="`#carousel-kegiatan-${item.id}`"
                        data-bs-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" :data-bs-target="`#carousel-kegiatan-${item.id}`"
                        data-bs-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Next</span>
                </button>
              </div>

              <!-- Deskripsi -->
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
    </section>

    <!-- Footer -->
    <FooterCentered />
  </div>
</template>

<style scoped>
.card-title {
  font-weight: 600;
}
</style>
<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import DefaultNavbar from "../../../examples/navbars/NavbarDefault.vue"
import FooterCentered from "../../../examples/footers/FooterCentered.vue"

const kegiatan = ref([])

onMounted(async () => {
  try {
    const response = await axios.get('/landing/kegiatan') 
    kegiatan.value = response.data
  } catch (err) {
    console.error('Gagal memuat kegiatan:', err)
  }
})
</script>
