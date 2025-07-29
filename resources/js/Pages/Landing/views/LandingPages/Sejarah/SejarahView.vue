<script setup>
// example components
import DefaultNavbar from "../../../examples/navbars/NavbarDefault.vue";
import FooterCentered from "../../../examples/footers/FooterCentered.vue";
import Header from "../../../examples/Header.vue";

// image
import image from "../../../assets/img/vue-mk-header.jpg";
</script>
<template>
   <!-- Navbar -->
    <div class="container position-sticky z-index-sticky top-0">
      <div class="row">
        <div class="col-12">
          <DefaultNavbar :sticky="true" :action="{ route: '', color: 'bg-gradient-success', label: 'Buy Now' }" />
        </div>
      </div>
    </div>
    
  <Header>
    <div class="page-header min-height-400" :style="{ backgroundImage: `url(${image})` }" loading="lazy">
      <span class="mask bg-gradient-dark opacity-8"></span>
    </div>
  </Header>

  <div class="card card-body shadow-xl mx-3 mx-md-4 mt-n6">
    <div class="col-lg-8 text-center mx-auto my-auto">
      <h1 class="text-success mb-4">SEJARAH MADRASAH</h1>
      <div class="text-dark opacity-8 text-justify text-base space-y-4 leading-relaxed">
        <p>
          MIN 1 Rokan Hulu merupakan salah satu lembaga pendidikan dasar Islam negeri yang berada di Kabupaten Rokan Hulu,
          Provinsi Riau. Madrasah ini resmi didirikan pada tanggal 1 Januari 1970 berdasarkan Surat Keputusan dari
          Kementerian Agama Republik Indonesia. Meski sudah beroperasi sejak awal 1970, SK operasional resminya baru
          diterbitkan pada 25 November 1995 dengan nomor 515A Tahun 1995.
        </p>
        <p>
          Keberadaan madrasah ini merupakan bagian dari upaya pemerintah dalam memperluas akses pendidikan Islam di daerah Rokan Hulu,
          terutama setelah wilayah ini resmi menjadi kabupaten sendiri yang terpisah dari Kabupaten Kampar. Secara geografis, MIN 1 Rokan Hulu
          berlokasi di Jalan Tasik Beringin No. 16, Desa Koto Tinggi, Kecamatan Rambah, yang juga merupakan pusat pemerintahan kabupaten.
        </p>
        <p>
          Sekolah ini berdiri di atas lahan seluas 4.177 meter persegi dan dilengkapi berbagai fasilitas penunjang pendidikan seperti
          ruang kelas, perpustakaan, sarana olahraga, ruang guru, serta akses internet. Berkat pengelolaan dan mutu pendidikan yang
          terus ditingkatkan, pada tanggal 9 September 2019, madrasah ini berhasil meraih akreditasi “A” dari Badan Akreditasi Nasional
          Sekolah/Madrasah (BAN-SM) berdasarkan SK Nomor 747/BAN-SM/SK/2019.
        </p>
        <p>
          Pendirian MIN 1 Rokan Hulu tidak terlepas dari dukungan berbagai elemen masyarakat, mulai dari tokoh agama, tokoh adat, tokoh
          pemuda, hingga pejabat Kementerian Agama setempat. Tujuan utamanya adalah menyediakan pendidikan dasar Islam formal yang berkualitas,
          terjangkau, dan dapat menjadi pilihan utama bagi masyarakat Rokan Hulu dan sekitarnya, terutama di wilayah Pasir Pengaraian.
        </p>
        <p>
          Seiring berjalannya waktu, madrasah ini terus berkembang dan menjadi salah satu institusi pendidikan Islam yang cukup berprestasi
          dan dipercaya masyarakat.
        </p>
      </div>
    </div>
  </div>

  <FooterCentered />
</template>
