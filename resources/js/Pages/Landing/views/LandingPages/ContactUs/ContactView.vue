<script setup>
import { onMounted } from "vue";

//example components
import DefaultNavbar from "../../../examples/navbars/NavbarDefault.vue";
import DefaultFooter from "../../../examples/footers/FooterDefault.vue";

//image
import image from "../../../assets/img/g2.jpg";

//material components
import MaterialInput from "../../../components/MaterialInput.vue";
import MaterialTextArea from "../../../components/MaterialTextArea.vue";
import MaterialButton from "../../../components/MaterialButton.vue";

// material-input
import setMaterialInput from "../../../assets/js/material-input";
onMounted(() => {
  setMaterialInput();
});
</script>
<template>
  <div class="container position-sticky z-index-sticky top-0">
    <div class="row">
      <div class="col-12">
        <DefaultNavbar
          :sticky="true"
          :action="{
            route: '',
            color: 'bg-gradient-success',
            label: 'Buy Now',
          }"
        />
      </div>
    </div>
  </div>
  <section>
    <div class="page-header min-vh-100">
      <div class="container">
        <div class="row">
          <div
            class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 start-0 text-center justify-content-center flex-column"
          >
            <div
              class="position-relative h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center"
              :style="{
                backgroundImage: `url(${image})`,
                backgroundSize: 'cover',
              }"
              loading="lazy"
            ></div>
          </div>
          <div
            class="mt-8 col-xl-5 col-lg-6 col-md-7 d-flex flex-column ms-auto me-auto ms-lg-auto me-lg-5"
          >
            <div
              class="card d-flex blur justify-content-center shadow-lg my-sm-0 my-sm-6 mt-8 mb-5"
            >
              <div
                class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent"
              >
                <div
                  class="bg-gradient-success shadow-success border-radius-lg p-3"
                >
                  <h3 class="text-white text-success mb-0">Kontak Kami</h3>
                </div>
              </div>
              <div class="card-body">
                <p class="pb-3">
                  Untuk Inforamasi lebih lengkap dapat hubungi kontak kami yang
                  ada di bawah ini.
                </p>
                <form id="contact-form" method="post" autocomplete="off">
                  <div class="card-body p-0 my-3">
                  <div class="row">
                      <div class="col-md-12">
                        <p>
                          <b>Hubungi:</b><br>
                          Hizrayati, S.Ag (085271670112)<br>
                          Sri Rismayani, S.Pd (082285416528)<br>
                          Gustami (082165136093)
                        </p>
                        <p>
                          <b>Email:</b> <a href="https://mail.google.com/mail/u/0/?view=cm&tf=1&fs=1&to=dataminpasirpengaraian@yahoo.co.id">dataminpasirpengaraian@yahoo.co.id</a>
                        </p>
                        <p>
                          <b>Facebook:</b> <a href="https://www.facebook.com/profile.php?id=61556266859011&mibextid=kFxxJD" target="_blank">Minsatu Rokanhulu</a>
                        </p>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12 text-center">
                        <MaterialButton
                          variant="gradient"
                          color="success"
                          class="mt-3 mb-0"
                        ><a href="https://wa.me/+6285271670112" target="_blank"
                        class="text-white">Kirim Pesan</a></MaterialButton>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <DefaultFooter />
</template>
