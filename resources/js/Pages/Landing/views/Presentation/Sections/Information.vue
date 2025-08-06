<script setup>
import RotatingCard from "../../../examples/cards/rotatingCards/RotatingCard.vue";
import RotatingCardFront from "../../../examples/cards/rotatingCards/RotatingCardFront.vue";
import RotatingCardBack from "../../../examples/cards/rotatingCards/RotatingCardBack.vue";
import DefaultInfoCard from "../../../examples/cards/infoCards/DefaultInfoCard.vue";
import imageUrl from '../../../assets/img/min/bersama.jpg';
</script>
<template>
  <section class="my-2 py-2">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 ms-auto me-auto p-lg-6 mt-lg-0 mt-6">
          <RotatingCard>
            <RotatingCardFront
              :image="imageUrl"
              icon="touch_app"
              title="Selamat Datang di Website MIN 1 ROKAN HULU"
              description="Semoga Informasi yang di tampilkan dapat membantu bapak dan ibu sekalian, sekian terima kasih."
            />

            <RotatingCardBack
              :image="imageUrl"
              title="Selamat Datang di Website MIN 1 ROKAN HULU"
              description="Semoga Informasi yang di tampilkan dapat membantu bapak dan ibu sekalian, sekian terima kasih."
              :action="[
                {
                  route: '#',
                  label: 'Thank You',
                },
              ]"
            />
          </RotatingCard>
        </div>
        <div class="col-lg-6 ms-auto">
          <div class="row justify-content-start">
            <DefaultInfoCard
              icon="content_copy"
              title="Edukasi Terbaik"
              description="Menyediakan lingkungan belajar yang kondusif dan kurikulum yang disesuaikan untuk menunjang potensi peserta didik secara maksimal."
            />
            <DefaultInfoCard
              icon="flip_to_front"
              title="Pengajar Terbaik"
              description="Didukung oleh tenaga pendidik berpengalaman, profesional, dan berdedikasi dalam membimbing siswa menjadi pribadi yang unggul."
            />
          </div>
          <div class="row justify-content-start mt-5">
            <DefaultInfoCard
              class="mt-3"
              icon="price_change"
              title="Sekolah Rekomendasi"
              description="Menjadi pilihan utama bagi orang tua dalam menyekolahkan anak karena kualitas pembelajaran dan fasilitas yang lengkap."
            />
            <DefaultInfoCard
              class="mt-3"
              icon="devices"
              title="Akreditasi A"
              description="Terakreditasi A oleh BAN-S/M sebagai bukti kualitas mutu pendidikan yang telah diakui secara nasional."
            />
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
