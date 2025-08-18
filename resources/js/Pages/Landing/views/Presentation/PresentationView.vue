<script setup>
import { ref, onMounted, onUnmounted } from "vue"
import NavbarDefault from "../../examples/navbars/NavbarDefault.vue"
import DefaultFooter from "../../examples/footers/FooterDefault.vue"
import Header from "../../examples/Header.vue"
import MaterialSocialButton from "../../components/MaterialSocialButton.vue"
import PresentationHeader from "./Sections/Header.vue"
import BuiltByDevelopers from "./Components/BuiltByDevelopers.vue"
import PresentationInformation from "./Sections/Information.vue"
import PresentationKamad from "./Sections/Kamad.vue"
import vueMkHeader from "../../assets/img/vue-mk-header.jpg"

const kamad = ref(null)
const body = document.body

onMounted(async () => {
  body.classList.add("presentation-page","bg-gray-200")
  try {
    const res = await fetch('/api/content/kamad', { headers: { Accept: 'application/json' } })
    if (res.ok) kamad.value = await res.json()
  } catch (e) { /* optional: tampilkan toast/log */ }
})

onUnmounted(() => body.classList.remove("presentation-page","bg-gray-200"))
</script>

<template>
  <div class="container position-sticky z-index-sticky top-0">
    <div class="row"><div class="col-12"><NavbarDefault :sticky="true" /></div></div>
  </div>

  <Header>
    <div class="page-header min-vh-75" :style="`background-image: url(${vueMkHeader})`" loading="lazy"></div>
  </Header>

  <div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-n6">
    <PresentationHeader />
    <PresentationInformation />

    <PresentationKamad v-if="kamad" :content="kamad" />
    <div v-else class="text-center text-muted py-5">Konten belum tersedia.</div>

    <BuiltByDevelopers />
  </div>

  <DefaultFooter />
</template>
