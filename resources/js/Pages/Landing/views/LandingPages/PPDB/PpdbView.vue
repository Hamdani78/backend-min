<script setup>
import FooterCentered from "../../../examples/footers/FooterCentered.vue"
import DefaultNavbar from "../../../examples/navbars/NavbarDefault.vue"
import HeaderView from "../PPDB/Sections/HeaderView.vue"

const currentYear = new Date().getFullYear()
const nextYear = currentYear + 1

const steps = [
  { icon: 'person_add', title: 'Daftar Online', desc: 'Membuat akun di portal', color: 'text-red-500' },
  { icon: 'badge', title: 'Lengkapi Data Diri', desc: 'Isi formulir pendaftaran', color: 'text-gray-800' },
  { icon: 'credit_card', title: 'Upload Berkas', desc: 'Unggah dokumen persyaratan', color: 'text-blue-500' },
  { icon: 'print', title: 'Cetak Bukti', desc: 'Print & tempel pas foto', color: 'text-green-500' },
  { icon: 'notifications', title: 'Verifikasi', desc: 'Validasi berkas oleh panitia', color: 'text-pink-500' },
  { icon: 'record_voice_over', title: 'Wawancara', desc: 'Dilakukan secara offline', color: 'text-purple-500' },
  { icon: 'campaign', title: 'Hasil Tes', desc: 'Pengumuman hasil seleksi', color: 'text-orange-400' },
  { icon: 'how_to_reg', title: 'Daftar Ulang', desc: 'Konfirmasi dan finalisasi', color: 'text-emerald-500' },
]
</script>

<template>
  <div>
    <!-- Navbar -->
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

    <!-- Judul PPDB -->
    <section class="my-5 py-5 flex flex-col gap-10">
      <HeaderView class="text-center" />

    <!-- Langkah-langkah PPDB -->
<div class="container">
  <div class="row justify-content-center align-items-center g-4">
    <template v-for="(step, index) in steps" :key="index">
      <!-- Kartu Langkah -->
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex justify-content-center">
        <div class="card shadow-sm text-center p-3" style="width: 16rem;">
          <span class="material-icons fs-1 mb-2" :class="step.color">
            {{ step.icon }}
          </span>
          <h6 class="fw-bold mb-1 text-dark">{{ step.title }}</h6>
          <p class="text-muted small mb-0">{{ step.desc }}</p>
        </div>
      </div>

      <!-- Panah -->
      <div
        v-if="index < steps.length - 1"
        class="d-none d-md-flex align-items-center justify-content-center"
        style="width: 2rem;"
      >
        <span class="material-icons text-secondary">arrow_forward</span>
      </div>
    </template>
  </div>
</div>


      <!-- Tombol Daftar -->
      <div class="text-end mt-5 pe-5">
        <a href="/user/login" class="btn btn-primary btn-lg shadow">
          Daftar Sekarang!
        </a>
      </div>
    </section>

    <!-- Footer -->
    <FooterCentered />
  </div>
</template>

<style scoped>
.w-64 {
  width: 16rem;
}
</style>
