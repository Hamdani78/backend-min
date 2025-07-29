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
      path: "/min/visimisi",
      name: "visimisi",
      component: VisimisiView,
    },
    {
      path: "/min/contact-us",
      name: "contactus",
      component: ContactView,
    },
    {
      path: "/min/sejarahsekolah",
      name: "sejarah",
      component: SejarahView,
    },

    {
      path: "/min/pegawai",
      name: "pegawai",
      component: PegawaiView,
    },
    // {
    //   path: "/min/pegawai/:id",
    //   name: "pegawai.detail",
    //   component: DetailPegawai,
    //   props: true,
    // },
    {
      path: "/min/siswa",
      name: "siswa",
      component: SiswaView,
    },
    {
      path: "/min/fasilitas",
      name: "fasilitas",
      component: FasilitasView,
    },
    {
      path: "/min/kegiatan",
      name: "kegiatan",
      component: KegiatanView,
    },
    {
      path: "/min/pendaftaran-peserta-didik-baru",
      name: "ppdb",
      component: PPDBView,
    },
  ],
});

export default router;
