<script setup>
import { ref, onMounted } from 'vue'
import CenteredBlogCard from "../../../examples/cards/blogCards/CenteredBlogCard.vue"
import image from "../../../assets/img/min/kamad.jpg"

const props = defineProps({
  slug: { type: String, default: 'kamad' } // slug konten
})

const data = ref(null)
const loading = ref(false)
const error = ref(null)

onMounted(async () => {
  try {
    loading.value = true
    const res = await fetch(`/api/content/${props.slug}`, { headers: { Accept: 'application/json' } })
    if (!res.ok) throw new Error(`HTTP ${res.status}`)
    data.value = await res.json()
  } catch (e) {
    error.value = e?.message ?? 'Gagal memuat'
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <section class="py-2">
    <div class="container">
      <div class="row text-center my-sm-5 mt-5">
        <div class="col-lg-6 mx-auto">
          <h2 class="text-dark mb-0">{{ data?.title ?? 'Kata Sambutan' }}</h2>
          <p class="lead">Kepala Madrasah</p>
        </div>
      </div>

      <div v-if="loading" class="text-center text-muted py-5">Memuat…</div>
      <div v-else-if="error" class="text-center text-red-600 py-5">Gagal memuat: {{ error }}</div>

      <div v-else-if="data" class="row align-items-center">
        <div class="col-lg-8">
          <div class="card">
            <div class="card-body text-dark mb-0">
              <div v-html="data.body"></div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 ms-auto mt-lg-0 mt-6">
          <CenteredBlogCard :image="image" title="Hizrayati, S.Ag" description="Kepala Madrasah." />
        </div>
      </div>

      <div v-else class="text-center text-muted py-5">Konten belum tersedia.</div>
    </div>
  </section>
</template>
