<script setup>
import DefaultInfoCard from '../../../../examples/cards/infoCards/DefaultInfoCard.vue';

</script>

<template>
  <section class="my-5 py-5">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-12 ms-5">
          <div class="row justify-content-start">
            <DefaultInfoCard
              icon="content_copy"
              title="Syarat Pendaftaran"
              description="Fotocopy Ijazah TK (2 lembar),
              Fotocopy Akte Kelahiran (2 lembar),
              Fotocopy Kartu Keluarga (KK) (2 lembar),
              Fotocopy Kartu Bantuan KIP (Jika Ada) (2 lembar)."
            />
            <DefaultInfoCard
              icon="flip_to_front"
              title="Syarat Lainnya"
              description="Umur Paling Rendah 6 Tahun 6 Bulan Buat yang tidak memiliki Ijazah TK per Juli 2024,
              Umur 6 Tahun bagi memiliki ijazah TK per Juni 2024,
              Kenal Angka, Huruf Abjad, dan Huruf Hijayyah,
              Mengisi Formulir yang Telah Disediakan Oleh Panitia."
            />
          </div>
          <div class="row justify-content-start mt-5">
            <DefaultInfoCard
              class="mt-2"
              icon="price_change"
              title="Waktu Pendaftaran"
              description="Pendaftaran dimulai tanggal 1 Februari 2024 s.d Kuota Penuh,
              Pukul 08.00 Wib s.d 16.00 Wib,
              Daftar Ulang pada Tanggal 22 Juni 2024."
            />
            <DefaultInfoCard
              class="mt-2"
              icon="devices"
              title="Sistem Belajar"
              description="Full Day Senin sampai Kamis masuk jam 07.00 wib pulang jam 16.00 wib,
              Setelah solat ashar, Jum'at masuk jam 07.00 wib pulang jam 11.30."
            />
          </div>
          <div class="row justify-content-start mt-5">
            <DefaultInfoCard
              class="mt-2"
              icon="home"
              title="Lokasi Pendaftaran"
              description="Pendaftaran dimulai tanggal 1 Februari 2024 s.d Kuota Penuh,
              Pukul 08.00 Wib s.d 16.00 Wib,
              Daftar Ulang pada Tanggal 22 Juni 2024,
              Jl. Tasik Beringin No. 16 Dusa Koto Tinggi Belakang Polsek Rambah."
            />
            <DefaultInfoCard
              class="mt-2"
              icon="devices"
              title="Informasi"
              description="Full Day Senin sampai Kamis masuk jam 07.00 wib pulang jam 16.00 wib,
              Setelah solat ashar, Jum'at masuk jam 07.00 wib pulang jam 11.30."
            />
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
