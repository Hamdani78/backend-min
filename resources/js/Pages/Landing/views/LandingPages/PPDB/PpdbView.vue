<script setup>
import FooterCentered from "../../../examples/footers/FooterCentered.vue"
import DefaultNavbar from "../../../examples/navbars/NavbarDefault.vue"
import HeaderView from "../PPDB/Sections/HeaderView.vue"
import AlurPPDBOnline from '../PPDB/Sections/AlurPPDBOnline.vue'

// Dapatkan tahun saat ini dan tahun berikutnya
const currentYear = new Date().getFullYear()
const nextYear = currentYear + 1
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

      <!-- Kotak Informasi -->
      <div class="d-flex flex-wrap justify-content-center gap-4 px-3">
        <!-- Syarat Pendaftaran -->
        <div class="bg-white shadow rounded p-4 w-64 text-center">
          <strong class="d-block mb-2">Syarat Pendaftaran</strong>
          <ul class="text-start text-sm">
            <li>Fotocopy Ijazah TK (2 lembar)</li>
            <li>Fotocopy Akte Kelahiran (2 lembar)</li>
            <li>Fotocopy KK (2 lembar)</li>
            <li>Fotocopy KIP (jika ada)</li>
          </ul>
        </div>

        <!-- Syarat Lainnya -->
        <div class="bg-white shadow rounded p-4 w-64 text-center border border-primary">
          <strong class="d-block mb-2">Syarat Lainnya</strong>
          <ul class="text-start text-sm">
            <li>Usia 6 tahun (dengan ijazah TK)</li>
            <li>Usia 6 tahun 6 bulan (tanpa ijazah)</li>
            <li>Kenal huruf, angka, hijaiyah</li>
            <li>Mengisi formulir panitia</li>
          </ul>
        </div>

        <!-- Waktu Pendaftaran -->
        <div class="bg-white shadow rounded p-4 w-64 text-center">
          <strong class="d-block mb-2">Waktu Pendaftaran</strong>
          <p class="text-sm">
            Mulai 1 Februari 2024<br />
            Sampai kuota penuh<br />
            Pukul 08.00 – 16.00 WIB<br />
            Daftar ulang 22 Juni 2024
          </p>
        </div>

        <!-- Informasi Lain -->
        <div class="bg-white shadow rounded p-4 w-64 text-center">
          <strong class="d-block mb-2">Informasi</strong>
          <p class="text-sm">
            Sistem Full Day<br />
            Senin–Kamis: 07.00–16.00<br />
            Jumat: 07.00–11.30
          </p>
        </div>
      </div>

      <div class="d-flex flex-wrap justify-content-center gap-4 px-3">
        <AlurPPDBOnline />
      </div>


      <!-- Tombol Daftar -->
      <div class="text-end mt-5 pe-5">
        <a :href="route('user.login')" class="btn btn-primary">Daftar Sekarang!</a>
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
