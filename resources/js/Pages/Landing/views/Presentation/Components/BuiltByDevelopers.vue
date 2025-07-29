<script setup>
import ImagePoster from "../../../assets/img/min/poster.jpg";
</script>
<template>
  <div class="container mt-sm-5">
    <div
      class="page-header py-6 py-md-5 my-sm-3 mb-3 border-radius-xl"
      :style="{
        backgroundImage: `url(${ImagePoster})`,
        backgroundSize: 'cover',
        backgroundPosition: '',
        height: '60vh',
      }"
      loading="lazy"
    >
      <span class="mask bg-gradient-dark"></span>
      <div class="container">
        <div class="row">
          <div class="col-lg-6 ms-lg-5">
            <h4 class="text-white">Dibuka Pendaftaran</h4>
            <h1 class="text-white">Peserta Didik Baru</h1>
            <p class="lead text-white opacity-8">
              Untuk informasi lengkapnya dapat dilihat pada laman PPDB kami.
            </p>
            <a href="/min/pendaftaran-peserta-didik-baru" class="text-white icon-move-right">
              Info Selengkapnya
              <i class="fas fa-arrow-right text-sm ms-1"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
