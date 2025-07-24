<script setup>
import { onMounted, onUnmounted } from "vue";

//example components
import DefaultNavbar from "../../../examples/navbars/NavbarDefault.vue";
import FooterCentered from "../../../examples/footers/FooterCentered.vue";

//image
import bg0 from "../../../assets/img/vue-mk-header.jpg";

//dep
import Typed from "typed.js";

const body = document.getElementsByTagName("body")[0];
//hooks
onMounted(() => {
  body.classList.add("about-us");
  body.classList.add("bg-gray-200");

  if (document.getElementById("typed")) {
    // eslint-disable-next-line no-unused-vars
    var typed = new Typed("#typed", {
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
  body.classList.remove("about-us");
  body.classList.remove("bg-gray-200");
});
</script>
<template>
  <DefaultNavbar
    :action="{
      route: 'javascript:;',
      label: 'Buy Now',
      color: 'btn-white',
    }"
    transparent
  />
  <header class="bg-gradient-dark">
    <div
      class="page-header min-vh-75"
      :style="{ backgroundImage: `url(${bg0})` }"
    >
      <span class="mask bg-gradient-dark opacity-6"></span>
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8 text-center mx-auto my-auto">
            <h1 class="text-white">
               <span class="text-white" id="typed"></span>
            </h1>
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
  </header>
  <div class="card card-body shadow-xl mx-3 mx-md-4 mt-n6">
    <div class="col-lg-8 text-center mx-auto my-auto">
      <h1 class="text-success">VISI</h1>
      <p class="lead mb-4 text-dark opacity-8">
        Meningkatkan siswa didik yang bertaqwa , berakhlak mulia,
        berkepribadian, berilmu, terampil dan mampu mengaktualisasikan diri
        dalam kehidupan masyarakat.
      </p>
    </div>
    <div class="col-lg-8 text-center mx-auto my-auto">
      <h1 class="text-success">MISI</h1>
      <p class="lead mb-4 text-dark opacity-8 text-start">
        1. Menciptakan lembaga pendidikan yang islami dan berkualitas.<br />
        2. Menciptakan peserta didik yang beriman dan bertaqwa.<br />
        3. Mengupayakan menjadi lembaga pendidikan yang sejajar dengan lembaga
        pendidikan SD lainnya, baik dari segi fasilitas, ketenagaan, dan
        siswa.<br />
        4. Menyelenggarakan proses pembelajaran yang menghasilkan lulusan yang
        berprestasi.
      </p>
    </div>
  </div>
  <footer-centered/>
</template>
