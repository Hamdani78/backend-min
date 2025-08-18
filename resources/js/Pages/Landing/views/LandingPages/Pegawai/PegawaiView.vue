<template>
  <div>
    <div class="container position-sticky z-index-sticky top-0">
      <div class="row">
        <div class="col-12">
          <DefaultNavbar :sticky="true" :action="{
            route: '',
            color: 'bg-gradient-success',
            label: 'PPDB',
          }" />
        </div>
      </div>
    </div>

    <!-- Section: padding/spacing diperkecil -->
    <section class="gtk-scope pt-3 pb-1 mt-7">
      <div class="container">
        <!-- Heading -->
        <div class="row justify-content-center text-center my-sm-3">
          <div class="col-12 col-lg-10 col-xl-8">
            <h2 class="gtk-title">Guru dan Tenaga Kependidikan</h2>
            <p class="gtk-subtitle">Daftar guru dan tenaga kependidikan MIN 1 Rokan Hulu</p>
            <p class="gtk-subtitle tahun">Tahun Ajaran {{ currentYear }}/{{ nextYear }}</p>
          </div>
        </div>

        <!-- Swiper -->
        <Swiper :modules="[Navigation, Pagination]" navigation :pagination="{ clickable: true }" :slides-per-view="1"
          :space-between="24" class="gtk-swiper">
          <!-- 1 slide = 1 board (card besar) berisi 8 item -->
          <SwiperSlide v-for="(group, idx) in chunkArray(pegawai, 8)" :key="idx">
            <div class="board">
              <!-- <div class="board-header">
                <span>Halaman {{ idx + 1 }}</span>
              </div> -->
              <div class="board-grid">
                <div v-for="item in group" :key="item.id" class="person-card">
                  <div class="photo">
                    <img :src="fotoUrl(item.foto)" alt="Foto Pegawai" loading="lazy" />
                  </div>

                  <div class="info">
                    <div class="info-row">
                      <span class="label">Nama</span>
                      <span class="text" :title="item.nama">: {{ item.nama }}</span>
                    </div>
                    <div class="info-row">
                      <span class="label">NIP</span>
                      <span class="text" :title="item.nip || '-'">: {{ item.nip || '-' }}</span>
                    </div>
                  </div>

                  <div class="actions">
                    <button class="btn-detail" data-bs-toggle="modal" data-bs-target="#detailModal"
                      @click="openModal(item)">
                      <i class="fas fa-address-card"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </SwiperSlide>
        </Swiper>
      </div>
    </section>

    <!-- Modal Detail Pegawai -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="detailModalLabel">Detail Pegawai</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body" v-if="selectedPegawai">
            <div class="row">
              <div class="col-md-4 text-center">
                <img :src="fotoUrl(selectedPegawai.foto)" class="img-fluid rounded"
                  style="max-height: 240px; object-fit: cover;" alt="Foto Pegawai" />
              </div>
              <div class="col-md-8 gtk-scope">
                <div class="info">
                  <div class="info-row">
                    <span class="label">Nama</span>
                    <span class="text">: {{ selectedPegawai.nama }}</span>
                  </div>
                  <div class="info-row">
                    <span class="label">NIP</span>
                    <span class="text">: {{ selectedPegawai.nip || '-' }}</span>
                  </div>
                  <div class="info-row">
                    <span class="label">Email</span>
                    <span class="text">: {{ selectedPegawai.email || '-' }}</span>
                  </div>
                  <div class="">
                    <span class="label">Bidang Ajar </span>
                    <span class="text">: {{ selectedPegawai.bidang_ajar || '-' }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <FooterCentered />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Navigation, Pagination } from 'swiper/modules'
import 'swiper/css'
import 'swiper/css/navigation'
import 'swiper/css/pagination'

import DefaultNavbar from '../../../examples/navbars/NavbarDefault.vue'
import FooterCentered from '../../../examples/footers/FooterCentered.vue'

const pegawai = ref([])
const selectedPegawai = ref(null)
const currentYear = new Date().getFullYear()
const nextYear = currentYear + 1

function openModal(item) {
  selectedPegawai.value = item
}

function fotoUrl(foto) {
  if (!foto) return '/images/no-image.png'
  return foto.startsWith?.('http') ? foto : `/storage/pegawai/${foto}`
}

function chunkArray(arr, size) {
  return Array.from({ length: Math.ceil(arr.length / size) }, (_, i) =>
    arr.slice(i * size, i * size + size)
  )
}

onMounted(async () => {
  try {
    const res = await axios.get('/landing/pegawai')
    pegawai.value = res.data
  } catch (err) {
    console.error('Gagal ambil data pegawai:', err)
  }
})
</script>

<style scoped>
/* ====== Headings ====== */
.gtk-scope .gtk-title {
  font-weight: 800;
  color: #111827;
  letter-spacing: .2px;
  line-height: 1.2;
  margin-bottom: .25rem;
  white-space: normal;
  word-break: normal;
}

.gtk-scope .gtk-subtitle {
  color: #6b7280;
  margin: 0 auto;
  max-width: 60ch;
  font-weight: 700;
  word-break: keep-all;
}

.gtk-scope .tahun {
  font-weight: 700;
  margin-top: 4px;
}

@media (max-width:576px) {
  .gtk-scope .gtk-title {
    font-size: 1.75rem;
  }
}

/* ====== Board per slide ====== */
.gtk-scope .board {
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 16px;
  padding: 20px;
  box-shadow: 0 10px 30px rgba(17, 24, 39, .06);
  margin-bottom: 0;
}

.gtk-scope .board-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 8px 4px 16px;
  color: #6b7280;
  font-size: 14px;
  border-bottom: 1px dashed #e5e7eb;
  margin-bottom: 14px;
}

/* ====== Grid 8 item: 4x2 / 3x2 / 2x2 ====== */
.gtk-scope .board-grid {
  display: grid;
  gap: 18px;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  margin-bottom: 0;
}

@media (min-width:768px) {
  .gtk-scope .board-grid {
    grid-template-columns: repeat(3, minmax(0, 1fr));
  }
}

@media (min-width:1024px) {
  .gtk-scope .board-grid {
    grid-template-columns: repeat(4, minmax(0, 1fr));
  }
}

/* ====== Card kecil ====== */
.gtk-scope .person-card {
  display: flex;
  flex-direction: column;
  min-height: 290px;
  border: 1px solid #ffe0a3;
  border-radius: 14px;
  background: #fff;
  padding: 14px;
  box-shadow: 0 6px 18px rgba(17, 24, 39, .05);
  transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
}

.gtk-scope .person-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 26px rgba(17, 24, 39, .08);
  border-color: #ffbf5a;
}

/* Foto */
.gtk-scope .photo {
  display: flex;
  justify-content: center;
}

.gtk-scope .photo img {
  width: 130px;
  height: 160px;
  object-fit: cover;
  border-radius: 10px;
  background: #f3f4f6;
}

/* Info */
.gtk-scope .info {
  margin-top: 12px;
  flex: 1 1 auto;
}

.gtk-scope .info-row {
  display: grid;
  grid-template-columns: 40px 1fr;
  gap: 8px;
  align-items: baseline;
  margin-bottom: 6px;
}

.gtk-scope .label {
  font-weight: 700;
  color: #6b7280;
  font-size: 13px;
}

.gtk-scope .text {
  color: #374151;
  font-size: 14px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* Actions */
.gtk-scope .actions {
  display: flex;
  justify-content: flex-end;
  margin-top: 8px;
}

.gtk-scope .btn-detail {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border: 1px solid #d1d5db;
  border-radius: 10px;
  background: #fff;
  color: #374151;
  transition: all .15s ease;
}

.gtk-scope .btn-detail:hover {
  border-color: #9ca3af;
  color: #111827;
  box-shadow: 0 4px 12px rgba(0, 0, 0, .07);
}

/* ====== Swiper controls & pagination (rapat ke bawah) ====== */
:deep(.gtk-scope .gtk-swiper .swiper-button-prev),
:deep(.gtk-scope .gtk-swiper .swiper-button-next) {
  color: #2563eb;
  width: 44px;
  height: 44px;
  background: #eff6ff;
  border-radius: 9999px;
  box-shadow: 0 4px 12px rgba(37, 99, 235, .15);
}

:deep(.gtk-scope .gtk-swiper .swiper-button-prev:after),
:deep(.gtk-scope .gtk-swiper .swiper-button-next:after) {
  font-size: 16px;
}

:deep(.gtk-scope .gtk-swiper .swiper-pagination) {
  bottom: 4px;
  /* rapatkan bullet */
  margin-bottom: 0;
}

:deep(.gtk-scope .gtk-swiper .swiper-pagination-bullet) {
  background: #c7d2fe;
  opacity: 1;
}

:deep(.gtk-scope .gtk-swiper .swiper-pagination-bullet-active) {
  background: #4f46e5;
}
</style>
