<script setup>
import FooterCentered from "../../../examples/footers/FooterCentered.vue";
import DefaultNavbar from "../../../examples/navbars/NavbarDefault.vue";
import FilledInfoCard from "../../../examples/cards/infoCards/FilledInfoCard.vue"

//import sections
import Information from "./Sections/Informations.vue";
import HeaderView from "./Sections/Header.vue";

// Dapatkan tahun saat ini dan tahun berikutnya
const currentYear = new Date().getFullYear();
const nextYear = currentYear + 1;
</script>

<script>
export default {
  inheritAttrs: false,
};
</script>

<template>
  <div>
    <!-- Navbar -->
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

    <!-- Konten Utama -->
    <section class="my-5 py-5">
      <div class="container">
        <HeaderView />
        <Information />
        <!-- <PosterView /> -->
      </div>
    </section>
    <div class="container">
      <div class="row">
        <div class="col-lg-4">
          <FilledInfoCard
            class="p-4"
            :color="{ text: 'white', background: 'bg-gradient-success' }"
            :icon="{ component: 'flag', color: 'white' }"
            title="Getting Started"
            description="Check the possible ways of working with our product and the necessary files for building your own project."
            :action="{
              route:
                'https://www.creative-tim.com/learning-lab/vue/overview/material-kit/',
              label: { text: 'Let\'s start', color: 'white' }
            }"
          />
        </div>
        <div class="col-lg-4">
          <FilledInfoCard
            class="px-lg-1 mt-lg-0 mt-4 p-4"
            height="h-100"
            :icon="{ component: 'precision_manufacturing', color: 'success' }"
            title="Plugins"
            description="Get inspiration and have an overview about the plugins that we
                used to create the Material Kit."
            :action="{
              route:
                'https://www.creative-tim.com/learning-lab/vue/input/material-kit/',
              label: { text: 'Read more' }
            }"
          />
        </div>
        <div class="col-lg-4">
          <FilledInfoCard
            class="px-lg-1 mt-lg-0 mt-4 p-4"
            :icon="{ component: 'receipt_long', color: 'success' }"
            title="Utility Classes"
            description="Material Kit is giving you a lot of pre-made elements. For those
                who want flexibility, we included many utility classes."
            :action="{
              route:
                'https://www.creative-tim.com/learning-lab/vue/utilities/material-kit/',
              label: { text: 'Read more' }
            }"
          />
        </div>
      </div>
    </div>
    <!-- Footer -->
    <FooterCentered />
  </div>
</template>
