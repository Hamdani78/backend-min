<script setup>
import CenteredBlogCard from "../../../examples/cards/blogCards/CenteredBlogCard.vue";
import image from "../../../assets/img/min/kamad.jpg";
</script>

<template>
  <section class="py-2">
    <div class="container">
      <div class="row">
        <div class="row text-center my-sm-5 mt-5">
          <div class="col-lg-6 mx-auto">
            <h2 class="text-dark mb-0">Kata Sambutan</h2>
            <p class="lead">Kepala Madrasah</p>
          </div>
        </div>
        <div class="row align-items-center">
          <div class="col-lg-8">
            <div class="card">
              <div class="card-body text-dark mb-0">
                <p>Assalamu’alaikum Warohmatullohi Wabarokatuh,</p>
                <p>
                  Puji syukur kita panjatkan kehadirat Allah SWT yang telah
                  memberikan karunia hidayah dan taufik-Nya serta kesehatan
                  sehingga kita masih mampu beraktivitas dengan nyaman sampai
                  hari ini.
                </p>
                <p>
                  Shalawat teriring salam kita sanjungkan kepada junjungan kita
                  Nabi Muhammad SAW beserta keluarganya, para sahabatnya dan
                  umatnya sampai akhir zaman.
                </p>
                <p>
                  Pendidikan adalah aset terbesar yang berperan dalam membangun
                  negara yang tinggi peradabannya, karena dengan pendidikanlah
                  SDM yang mengisi pembangunan negara ini mampu menjaga
                  persatuan dan kesatuan, keutuhan dan kelestarian lingkungan
                  hidupnya.
                </p>
                <p>
                  Dalam rangka ikut berperan dalam bidang pendidikan, MI Negeri
                  1 Rokan Hulu, menyiapkan program pendidikan yang berkualitas
                  dengan lingkungan yang sehat, asri dan nyaman serta didukung
                  oleh tenaga pendidik yang profesional serta sarana yang
                  memadai akan menghasilkan lulusan yang Cerdas, Disiplin,
                  Inovatif, dan Kompetitif.
                </p>
                <p>
                  Penanaman karakter pada siswa merupakan hal terpenting dalam
                  proses pembelajaran di sekolah kami, yaitu Religius, Jujur,
                  Adil, Berpikir Kritis, Bekerja Keras, Peduli, Bertanggung
                  Jawab, Komunikatif dan Literat.
                </p>
                <p>
                  Berikut ini profil sekolah kami, selamat menikmati dan kami
                  tunggu kedatangan Bapak dan Ibu di MIN 1 Rokan Hulu.
                </p>
                <p>
                  Wabillahi taufik wal hidayah wassalamu’alaikum Warohmatullohi
                  Wabarokatuh
                </p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 ms-auto mt-lg-0 mt-6">
            <CenteredBlogCard
              :image="image"
              title="Hizrayati,S.Ag"
              description="Kepala Madrasah."
            />
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
