<script setup>
// example components
import DefaultNavbar from "../../../examples/navbars/NavbarDefault.vue";
import FooterCentered from "../../../examples/footers/FooterCentered.vue";
import Header from "../../../examples/Header.vue";

// image
import image from "../../../assets/img/vue-mk-header.jpg";
</script>
<template>
  <DefaultNavbar transparent />
  <Header>
    <div
      class="page-header min-height-400"
      :style="{ backgroundImage: `url(${image})` }"
      loading="lazy"
    >
      <span class="mask bg-gradient-dark opacity-8"></span>
    </div>
  </Header>
  <div class="card card-body shadow-xl mx-3 mx-md-4 mt-n6">
    <div class="col-lg-8 text-center mx-auto my-auto">
      <h1 class="text-success">SEJARAH MADRASAH</h1>
      <p class="lead mb-4 text-dark opacity-8">
        Kabupaten Rokan Hulu, hasil pemekaran dari Kabupaten Kampar, yang
        berdiri pada tanggal 12 Oktober 1999 berdasarkan kepada UU Nomor 53
        tahun 1999 dan UU No 11 tahun 2003 tentang perubahan UU RI No 53 tahun
        1999. Jumlah penduduk Rokan Hulu sekitar 561.385 orang pada tahun 2020
        dengan luas wilayah 7.588,13 km², dan pada pertengahan tahun 2023
        berjumlah 568.004 orang. Kabupaten ini telah dijuluki sebagai
        Negeri Seribu Suluk yang artinya menjalankan kedisiplinan dalam
        menjalankan aturan-aturan agama Islam. Banya terdapat suluk atau masjid
        yang berdiri di kabupaten ini.
      </p>
    </div>
  </div>
  <FooterCentered />
</template>
