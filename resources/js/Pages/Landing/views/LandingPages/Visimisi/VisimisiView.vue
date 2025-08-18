<script setup>
import { ref, onMounted, onUnmounted } from "vue"
import Typed from "typed.js"

import DefaultNavbar from "../../../examples/navbars/NavbarDefault.vue"
import FooterCentered from "../../../examples/footers/FooterCentered.vue"
import Header from "../../../examples/Header.vue"
import bg0 from "../../../assets/img/vue-mk-header.jpg"

const visi  = ref(null)
const misi  = ref(null)
const loading = ref(true)
const error   = ref(null)

const body = document.body
let typed
const ctrl = new AbortController()

async function fetchContent(slug){
  const res = await fetch(`/api/content/${slug}`, { headers:{Accept:"application/json"}, signal: ctrl.signal })
  if (res.status === 404) return null
  if (!res.ok) throw new Error(`HTTP ${res.status}`)
  return await res.json()
}

onMounted(async () => {
  body.classList.add("about-us","bg-gray-200")
  try {
    ;[visi.value, misi.value] = await Promise.all([fetchContent("visi"), fetchContent("misi")])

    const strings = []
    if (visi.value?.title) strings.push(visi.value.title)
    if (visi.value?.title && misi.value?.title) strings.push("&")
    if (misi.value?.title) strings.push(misi.value.title)

    if (strings.length && document.getElementById("typed")) {
      typed = new Typed("#typed", {
        strings,
        typeSpeed: 90,
        backSpeed: 90,
        backDelay: 200,
        startDelay: 500,
        loop: true,
      })
    }
  } catch (e) {
    if (e?.name !== "AbortError") error.value = e?.message ?? "Gagal memuat konten"
  } finally {
    loading.value = false
  }
})

onUnmounted(() => {
  ctrl.abort()
  if (typed) typed.destroy()
  body.classList.remove("about-us","bg-gray-200")
})
</script>

<template>
  <!-- Navbar -->
  <div class="container position-sticky z-index-sticky top-0">
    <div class="row"><div class="col-12">
      <DefaultNavbar :sticky="true" :action="{ route: '', color: 'bg-gradient-success', label: 'Buy Now' }" />
    </div></div>
  </div>

  <!-- Header -->
  <Header>
    <div class="page-header min-vh-75" :style="{ backgroundImage: `url(${bg0})` }">
      <span class="mask bg-gradient-dark opacity-6"></span>
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8 text-center mx-auto my-auto">
            <h1 class="text-white"><span class="text-white" id="typed"></span></h1>
            <h1 class="text-white">MADRASAH IBTIDAIYAH NEGERI 1 ROKAN HULU</h1>
          </div>
        </div>
      </div>
    </div>
  </Header>

  <!-- Visi & Misi -->
  <section class="pt-2 pb-7 bg-gray-200">
    <div class="container">
      <div v-if="loading" class="text-center text-muted py-5">Memuat…</div>
      <div v-else-if="error" class="text-center text-red-600 py-5">Gagal memuat: {{ error }}</div>

      <div v-else class="row justify-content-center">
        <div v-if="visi" class="col-md-6 mb-4">
          <div class="card shadow border-0 h-100">
            <div class="card-header bg-gradient-success text-white text-center py-3 rounded-top">
              <h4 class="mb-0">{{ visi.title }}</h4>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center text-center">
              <div class="text-lg text-black font-bold px-2 w-100" v-html="visi.body"></div>
            </div>
          </div>
        </div>

        <div v-if="misi" class="col-md-6 mb-4">
          <div class="card shadow border-0 h-100">
            <div class="card-header bg-gradient-success text-white text-center py-3 rounded-top">
              <h4 class="mb-0">{{ misi.title }}</h4>
            </div>
            <div class="card-body">
              <div class="text-lg text-black font-bold" v-html="misi.body"></div>
            </div>
          </div>
        </div>

        <div v-if="!visi && !misi" class="text-center text-muted py-5">
          Konten Visi & Misi belum tersedia.
        </div>
      </div>
    </div>
  </section>

  <FooterCentered />
</template>
