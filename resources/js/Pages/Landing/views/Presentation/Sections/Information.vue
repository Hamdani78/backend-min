<script setup>
import RotatingCard from "../../../examples/cards/rotatingCards/RotatingCard.vue";
import RotatingCardFront from "../../../examples/cards/rotatingCards/RotatingCardFront.vue";
import RotatingCardBack from "../../../examples/cards/rotatingCards/RotatingCardBack.vue";
import DefaultInfoCard from "../../../examples/cards/infoCards/DefaultInfoCard.vue";
</script>
<template>
  <section class="my-2 py-2">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 ms-auto me-auto p-lg-6 mt-lg-0 mt-6">
          <RotatingCard>
            <RotatingCardFront
              image="./src/assets/img/min/bersama.jpg"
              icon="touch_app"
              title="Selamat Datang di Website MIN 1 ROKAN HULU"
              description="Semoga Informasi yang di tampilkan dapat membantu bapak dan ibu sekalian, sekian terima kasih."
            />

            <RotatingCardBack
              image="./src/assets/img/min/bersama.jpg"
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
              description="Built by developers for developers. Check the foundation and
                  you will find everything inside our documentation."
            />
            <DefaultInfoCard
              icon="flip_to_front"
              title="Pengajar Terbaik"
              description="The world’s most popular front-end open source toolkit,
                  featuring Sass variables and mixins."
            />
          </div>
          <div class="row justify-content-start mt-5">
            <DefaultInfoCard
              class="mt-3"
              icon="price_change"
              title="Sekolah Rekomendasi"
              description="Creating your design from scratch with dedicated designers can
                be very expensive. Start with our Design System."
            />
            <DefaultInfoCard
              class="mt-3"
              icon="devices"
              title="Akreditasi A"
              description="Regardless of the screen size, the website content will
                  naturally fit the given resolution."
            />
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
