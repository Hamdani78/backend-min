<script setup>
import DefaultNavbar from "../../../examples/navbars/NavbarDefault.vue";
import CenteredFooter from "../../../examples/footers/FooterCentered.vue";

defineProps({
  breadcrumb: {
    type: Array,
    required: true,
  },
  title: {
    type: String,
    required: true,
  },
});
</script>
<template>
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
  <div class="container mt-8">  
    <div class="row">
      <div class="col-lg-12 mx-auto">
        <div class="mb-4 w-100 w-md-50 w-lg-25">
          <h3>{{ title }}</h3>
        </div>

        <slot />
      </div>
    </div>
  </div>
  <CenteredFooter />
</template>
