<script setup>

// Dapatkan tahun saat ini dan tahun berikutnya
const currentYear = new Date().getFullYear();
const nextYear = currentYear + 1;
</script>

<template>
  <section class="pt-3 pb-4" id="count-stats">
    <div class="container">
      <div class="row">
        <div class="col-lg-9 z-index-2 border-radius-xl mx-auto py-3">
          <div class="row">
            <div class="container">
              <div class="row">
                <div class="text-center mx-auto position-relative">
                  <h1
                    class="text-success pt-3 mt-n5 me-2"
                    :style="{ display: 'inline-block ' }"
                  >
                    PENDAFTARAN PESERTA DIDIK BARU TAHUN AJARAN {{ currentYear }}/{{ nextYear }}
                  </h1>
                  <p
                    class="lead text-success px-5 mt-3"
                    :style="{ fontWeight: '500' }"
                  >
                    Madrasah Hebat Madrasah Bermartabat.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
