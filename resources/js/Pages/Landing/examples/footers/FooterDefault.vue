<script setup>
import logoDark from "../../assets/img/ic_logo.png";
defineProps({
  brand: {
    type: Object,
    name: String,
    logo: String,
    route: "",
    default: () => ({
      name: "MIN 1 ROHUL",
      logo: logoDark,
      route: "/"
    })
  },
  socials: {
    type: Array,
    icon: String,
    link: String,
    default: () => [
      {
        icon: '<i class="fab fa-facebook text-lg opacity-8"></i>',
        link: "https://www.facebook.com/profile.php?id=61556266859011&mibextid=kFxxJD"
      },
      {
        icon: '<i class="fab fa-whatsapp text-lg opacity-8"></i>',
        link: "https://wa.me/+6285271670112"
      },
      {
        icon: '<i class="far fa-envelope text-lg opacity-8"></i>',
        link: "https://mail.google.com/mail/u/0/?view=cm&tf=1&fs=1&to=dataminpasirpengaraian@yahoo.co.id",
      },
    ]
  },
  menus: {
    type: Array,
    name: String,
    items: {
      type: Array,
      name: String,
      href: String
    },
    default: () => [
      {
        name: "company",
        items: [
          {
            name: "Tentang Kami",
            href: "http://localhost:8000/"
          },
        ]
      },
      {
        name: "resources",
        items: [
          { 
            name: "VISI MISI", 
            href: "http://localhost:8000/pages/landing-pages/visimisi" },
          {
            name: "Sejarah Madrasah",
            href: "http://localhost:8000/pages/landing-pages/sejarahsekolah"
          },
          {
            name: "Guru dan Tenaga Kependidikan",
            href: "#"
          },
          {
            name: "Data Siswa",
            href: "#"
          },
        ]
      },
      {
        name: "help & support",
        items: [
          {
            name: "Kontak Kami",
            href: "http://localhost:8000/pages/landing-pages/contact-us"
          },
          {
            name: "development",
            href: "https://www.instagram.com/_hmdnif_/"
          },
        ]
      },
      {
        name: "legal",
        items: [
          {
            name: "privacy policy",
            href: "#"
          },
        ]
      }
    ]
  }
});
</script>
<template>
  <footer class="footer pt-5 mt-5">
    <div class="container">
      <div class="row">
        <div class="col-md-3 mb-4 ms-auto">
          <div>
            <a :href="brand.route">
              <img :src="brand.logo" class="mb-3 footer-logo" alt="main_logo" />
            </a>
            <h6 class="font-weight-bolder mb-4">{{ brand.name }}</h6>
          </div>
          <div>
            <ul class="d-flex flex-row ms-n3 nav">
              <li
                class="nav-item"
                v-for="{ icon, link } of socials"
                :key="link"
              >
                <a
                  class="nav-link pe-1"
                  :href="link"
                  target="_blank"
                  v-html="icon"
                >
                </a>
              </li>
            </ul>
          </div>
        </div>
        <div
          class="col-md-2 col-sm-6 col-6 mb-4"
          v-for="{ name, items } of menus"
          :key="name"
        >
          <h6 class="text-sm">{{ name }}</h6>
          <ul class="flex-column ms-n3 nav">
            <li class="nav-item" v-for="item of items" :key="item.name">
              <a class="nav-link" :href="item.href" target="_blank">
                {{ item.name }}
              </a>
            </li>
          </ul>
        </div>

        <div class="col-12">
          <div class="text-center">
            <p class="text-dark my-4 text-sm font-weight-normal">
              All rights reserved. Copyright ©
              {{ new Date().getFullYear() }}
              MIN1ROKANHULU by
              <a href="https://www.instagram.com/_hmdnif_/" target="_blank"
                >_hmdnif_</a
              >.
            </p>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>
