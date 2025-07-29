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
    <div class="page-header min-vh-75" :style="{ backgroundImage: `url(${bg0})` }">
      <span class="mask bg-gradient-dark opacity-6"></span>
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8 text-center mx-auto my-auto">
            <h1 class="text-white"><span class="text-white" id="typed"></span></h1>
            <div id="typed-strings">
              <h1>VISI</h1>
              <h1>&</h1>
              <h1>MISI</h1>
            </div>
            <h1 class="text-white">MADRASAH IBTIDAIYAH NEGERI 1 ROKAN HULU</h1>
          </div>
        </div>
      </div>
    </div>
  </Header>

  <!-- Visi Misi Section -->
<section class="pt-2 pb-7 bg-gray-200">
    <div class="container">
        <div class="row justify-content-center">
          <!-- Card VISI -->
          <div class="col-md-6 mb-4">
            <div class="card shadow border-0 h-100">
              <div class="card-header bg-gradient-success text-white text-center py-3 rounded-top">
                <h4 class="mb-0">VISI</h4>
              </div>
              <div class="card-body d-flex align-items-center justify-content-center text-center">
                <p class="text-lg text-black font-bold px-2">
                  Meningkatkan siswa didik yang bertaqwa, berakhlak mulia, berkepribadian, berilmu, terampil dan mampu
                  mengaktualisasikan diri dalam kehidupan masyarakat.
                </p>
              </div>
            </div>
          </div>

          <!-- Card MISI -->
          <div class="col-md-6 mb-4">
            <div class="card shadow border-0 h-100">
              <div class="card-header bg-gradient-success text-white text-center py-3 rounded-top">
                <h4 class="mb-0">MISI</h4>
              </div>
              <div class="card-body">
                <ul class="text-lg text-black font-bold ps-5 mb-0">
                  <li class="mb-2">Menciptakan lembaga pendidikan yang islami dan berkualitas.</li>
                  <li class="mb-2">Menciptakan peserta didik yang beriman dan bertaqwa.</li>
                  <li class="mb-2">
                    Mengupayakan menjadi lembaga pendidikan yang sejajar dengan SD lainnya, baik dari segi fasilitas,
                    ketenagaan, dan siswa.
                  </li>
                  <li>Menyelenggarakan proses pembelajaran yang menghasilkan lulusan yang berprestasi.</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
  </section>

  <FooterCentered />
</template>

<script setup>
import { onMounted, onUnmounted } from "vue";
import DefaultNavbar from "../../../examples/navbars/NavbarDefault.vue";
import FooterCentered from "../../../examples/footers/FooterCentered.vue";
import Header from "../../../examples/Header.vue";
import bg0 from "../../../assets/img/vue-mk-header.jpg";
import Typed from "typed.js";

const body = document.getElementsByTagName("body")[0];

onMounted(() => {
  body.classList.add("about-us", "bg-gray-200");
  if (document.getElementById("typed")) {
    new Typed("#typed", {
      stringsElement: "#typed-strings",
      typeSpeed: 90,
      backSpeed: 90,
      backDelay: 200,
      startDelay: 500,
      loop: true,
    });
  }
});

onUnmounted(() => {
  body.classList.remove("about-us", "bg-gray-200");
});
</script>
