<script setup>
import { ref, onMounted, onUnmounted } from "vue"

// components
import DefaultNavbar from "../../../examples/navbars/NavbarDefault.vue"
import FooterCentered from "../../../examples/footers/FooterCentered.vue"
import Header from "../../../examples/Header.vue"

// assets
import image from "../../../assets/img/vue-mk-header.jpg"

// state
const content = ref(null)
const loading = ref(true)
const error   = ref(null)

const ctrl = new AbortController()

onMounted(async () => {
  document.body.classList.add("presentation-page","bg-gray-200")
  try {
    const res = await fetch("/api/content/sejarah", {
      headers: { Accept: "application/json" },
      signal: ctrl.signal
    })
    if (!res.ok) throw new Error(`HTTP ${res.status}`)
    content.value = await res.json()
  } catch (e) {
    if (e?.name !== "AbortError") error.value = e?.message ?? "Gagal memuat"
  } finally {
    loading.value = false
  }
})

onUnmounted(() => {
  ctrl.abort()
  document.body.classList.remove("presentation-page","bg-gray-200")
})
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

  <!-- Header -->
  <Header>
    <div class="page-header min-height-400" :style="{ backgroundImage: `url(${image})` }" loading="lazy">
      <span class="mask bg-gradient-dark opacity-8"></span>
    </div>
  </Header>

  <!-- Body -->
  <div class="card card-body shadow-xl mx-3 mx-md-4 mt-n6">
    <div class="col-lg-8 text-center mx-auto my-auto">
      <h1 class="text-success mb-4">{{ content?.title ?? 'SEJARAH MADRASAH' }}</h1>

      <div v-if="loading" class="text-muted py-4">Memuat…</div>
      <div v-else-if="error" class="text-red-600 py-4">Gagal memuat: {{ error }}</div>

      <!-- dari admin (boleh HTML) -->
      <div v-else-if="content" class="text-dark opacity-8 text-justify text-base space-y-4 leading-relaxed" v-html="content.body"></div>

      <!-- fallback singkat -->
      <div v-else class="text-muted py-4">Konten belum tersedia.</div>
    </div>
  </div>

  <FooterCentered />
</template>
