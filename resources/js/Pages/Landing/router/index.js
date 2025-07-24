import { createRouter, createWebHistory } from "vue-router";
import PresentationView from "../views/Presentation/PresentationView.vue";
import VisimisiView from "../views/LandingPages/Visimisi/VisimisiView.vue";
import ContactView from "../views/LandingPages/ContactUs/ContactView.vue";
import SejarahView from "../views/LandingPages/Sejarah/SejarahView.vue";

import PegawaiView from "../views/LandingPages/Pegawai/PegawaiView.vue";
import SiswaView from "../views/LandingPages/Siswa/SiswaView.vue";
import FasilitasView from "../views/LandingPages/Fasilitas/FasilitasView.vue";
import KegiatanView from "../views/LandingPages/Kegiatan/KegiatanView.vue";
import PPDBView from "../views/LandingPages/PPDB/PpdbView.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "presentation",
      component: PresentationView,
    },
    {
      path: "/pages/visimisi",
      name: "visimisi",
      component: VisimisiView,
    },
    {
      path: "/pages/contact-us",
      name: "contactus",
      component: ContactView,
    },
    {
      path: "/pages/sejarahsekolah",
      name: "sejarah",
      component: SejarahView,
    },
    // {
    //   path: "/pages/landing-pages/basic",
    //   name: "signin-basic",
    //   component: SignInBasicView,
    // },

    // sampai sini

    {
      path: "/pages/pegawai",
      name: "pegawai",
      component: PegawaiView,
    },
    {
      path: "/page/siswa",
      name: "siswa",
      component: SiswaView,
    },
    {
      path: "/page/fasilitas",
      name: "fasilitas",
      component: FasilitasView,
    },
    {
      path: "/page/kegiatan",
      name: "kegiatan",
      component: KegiatanView,
    },
    {
      path: "/page/pendaftran-peserta-didik-baru",
      name: "ppdb",
      component: PPDBView,
    },
  ],
});

export default router;
