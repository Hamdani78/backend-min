<script setup>
import FooterCentered from "../../../examples/footers/FooterCentered.vue";
import DefaultNavbar from "../../../examples/navbars/NavbarDefault.vue";
</script>
  <script>
export default {
  inheritAttrs: false,
};
</script>

<template>
  <div>
    <div class="container position-sticky z-index-sticky top-0">
      <div class="row">
        <div class="col-12">
          <DefaultNavbar
            :sticky="true"
            :action="{
              route: '',
              color: 'bg-gradient-success',
              label: 'Buy Now',
            }"
          />
        </div>
      </div>
    </div>
    <section class="my-5 py-5">
      <div class="container">
        <div class="row">
          <div class="row justify-content-center text-center my-sm-5">
            <div class="col-lg-6">
              <h2 class="text-dark mb-0">Guru dan Tenaga Kependidikan</h2>
              <p class="lead">
                Daftar nama - nama guru dan tenaga kependidikan Madrasah
                Ibtidaiyah Negeri 1 Rokan Hulu
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <FooterCentered />
  </div>
</template>
