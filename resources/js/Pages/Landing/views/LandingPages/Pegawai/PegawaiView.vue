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

    <!-- Header -->
    <section class="my-5 py-5">
      <div class="container">
        <div class="row justify-content-center text-center my-sm-5">
          <div class="col-lg-6">
            <h2 class="text-dark mb-0">Guru dan Tenaga Kependidikan</h2>
            <p class="lead text-secondary">
              Daftar nama guru dan tenaga kependidikan MIN 1 Rokan Hulu.
            </p>
          </div>
        </div>

        <!-- Grid Pegawai -->
        <div class="row justify-content-center">
          <div v-for="item in pegawai" :key="item.id"
            class="col-sm-6 col-md-4 col-lg-3 mb-4 d-flex justify-content-center">
            <div class="card shadow-sm d-flex flex-column h-100 px-3 py-3"
              style="border: 1px solid #ffa726; border-radius: 12px;">
              <!-- Foto -->
              <img :src="`/storage/pegawai/${item.foto}`" alt="Foto Pegawai" class="mx-auto mb-3"
                style="width: 120px; height: 160px; object-fit: cover; border-radius: 6px;" />

              <!-- Data -->
              <div class="flex-grow-1 text-start">
                <p class="mb-1 text-secondary" style="font-size: 14px;">
                  <strong>Nama</strong> :  {{ item.nama }}
                </p>
                <p class="mb-1 text-secondary" style="font-size: 14px;">
                  <strong>NIP</strong> :  {{ item.nip || '-' }}
                </p>
              </div>

              <!-- Tombol Detail -->
              <div class="d-flex justify-content-end mt-2">
                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal"
                  @click="openModal(item)">
                  <i class="fas fa-address-card fa-lg"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
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
                <img :src="`/storage/pegawai/${selectedPegawai.foto}`" class="img-fluid rounded"
                  style="max-height: 240px; object-fit: cover;" alt="Foto Pegawai" />
              </div>
              <div class="col-md-8">
                <p><strong>Nama :</strong> {{ selectedPegawai.nama }}</p>
                <p><strong>NIP :</strong> {{ selectedPegawai.nip || '-' }}</p>
                <p><strong>Email :</strong> {{ selectedPegawai.email || '-' }}</p>
                <p><strong>Bidang Ajar :</strong> {{ selectedPegawai.status || '-' }}</p>
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
import DefaultNavbar from '../../../examples/navbars/NavbarDefault.vue'
import FooterCentered from '../../../examples/footers/FooterCentered.vue'

const pegawai = ref([])
const selectedPegawai = ref(null)

function openModal(item) {
  selectedPegawai.value = item
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
