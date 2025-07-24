<script setup>
import { onMounted, onUnmounted } from "vue";

//example components
import NavbarDefault from "../../examples/navbars/NavbarDefault.vue";
import DefaultFooter from "../../examples/footers/FooterDefault.vue";
import Header from "../../examples/Header.vue";


//Vue Material Kit 2 components
import MaterialSocialButton from "../../components/MaterialSocialButton.vue";

// sections
import PresentationHeader from "./Sections/Header.vue";
import PresentationPages from "./Sections/Pages.vue";
import PresentationExample from "./Sections/Example.vue";
import data from "./Sections/Data/designBlocksData";
import BuiltByDevelopers from "./Components/BuiltByDevelopers.vue";
import PresentationInformation from "./Sections/Information.vue";
import PresentationKamad from "./Sections/Kamad.vue"

//images
import vueMkHeader from "../../assets/img/vue-mk-header.jpg";


//hooks
const body = document.getElementsByTagName("body")[0];
onMounted(() => {
  body.classList.add("presentation-page");
  body.classList.add("bg-gray-200");
});
onUnmounted(() => {
  body.classList.remove("presentation-page");
  body.classList.remove("bg-gray-200");
});
</script>

<template>
  <div class="container position-sticky z-index-sticky top-0">
    <div class="row">
      <div class="col-12">
        <NavbarDefault :sticky="true" />
      </div>
    </div>
  </div>
  <Header>
    <div
      class="page-header min-vh-75"
      :style="`background-image: url(${vueMkHeader})`"
      loading="lazy"
    >
    </div>
  </Header>

  <div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-n6">
    <PresentationHeader />
    <PresentationInformation />
    <PresentationKamad />
    <PresentationExample :data="data" />
    <PresentationPages /> 
    <BuiltByDevelopers />

    <div class="py-5">
      <div class="container">
        <div class="row">
          <div class="col-lg-5 ms-auto">
            <h4 class="mb-1">Terima Kasih atas kunjungannya!</h4>
            <p class="lead mb-0">Thank You!</p>
          </div>
          <div class="col-lg-5 me-lg-auto my-lg-auto text-lg-end mt-5">
            <MaterialSocialButton
              route="https://www.facebook.com/profile.php?id=61556266859011&mibextid=kFxxJD"
              component="facebook-square"
              color="facebook"
              label="facebook"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
  <DefaultFooter />
</template>
