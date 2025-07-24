<script setup>
defineProps({
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
    ],
  },
  links: {
    type: Array,
    name: String,
    href: String,
    default: () => [
      { href: "http://localhost:8000/", name: "Company" },
      { href: "#", name: "Tentang Kami" },
      {
        href: "#",
        name: "Guru dan Tenaga Kependidikan",
      },
      { href: "#", name: "Siswa" },
      { href: "#", name: "Kontak Kami" },
    ],
  },
});
</script>
<template>
  <footer class="footer py-5">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 mb-4 mx-auto text-center">
          <a
            v-for="{ name, href } of links"
            :key="name"
            :href="href"
            target="_blank"
            class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2"
          >
            {{ name }}
          </a>
        </div>
        <div class="col-lg-8 mx-auto text-center mb-4 mt-2">
          <a
            v-for="{ icon, link } of socials"
            :key="link"
            :href="link"
            target="_blank"
            class="text-secondary me-xl-4 me-4"
            v-html="icon"
          >
          </a>
        </div>
      </div>
      <div class="row">
        <div class="col-8 mx-auto text-center mt-1">
          <p class="mb-0 text-secondary">
            All rights reserved. Copyright ©
              {{ new Date().getFullYear() }}
              min1rokanhulu by
              <a href="https://www.instagram.com/_hmdnif_/" target="_blank"
                >_hmdnif_</a
              >.
          </p>
        </div>
      </div>
    </div>
  </footer>
</template>
