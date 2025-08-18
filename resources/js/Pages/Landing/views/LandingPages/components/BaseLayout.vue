<script setup>
import DefaultNavbar from "../../../examples/navbars/NavbarDefault.vue"
import CenteredFooter from "../../../examples/footers/FooterCentered.vue"

const props = defineProps({
  title: { type: String, default: "" },
  breadcrumb: { type: Array, default: () => [] }
})

</script>

<template>
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

  <!-- Konten halaman -->
  <main class="container mt-4"> <!-- was mt-8 -->
    <div class="row">
      <div class="col-lg-12 mx-auto">
        <div class="mb-4">
          <slot name="page-title">
            <h3 class="mb-1">{{ title }}</h3>
            <nav v-if="breadcrumb.length" aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li
                  v-for="(bc, i) in breadcrumb"
                  :key="i"
                  class="breadcrumb-item"
                  :class="{ active: i === breadcrumb.length - 1 }"
                  :aria-current="i === breadcrumb.length - 1 ? 'page' : null"
                >
                  <template v-if="bc.href && i !== breadcrumb.length - 1">
                    <a :href="bc.href">{{ bc.label }}</a>
                  </template>
                  <template v-else>{{ bc.label }}</template>
                </li>
              </ol>
            </nav>
          </slot>
        </div>

        <slot />
      </div>
    </div>
  </main>

  <CenteredFooter />
</template>
