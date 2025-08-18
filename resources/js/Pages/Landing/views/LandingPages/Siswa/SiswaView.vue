<template>
  <div>
    <BaseLayout>
      <!-- Header ala pegawai, jarak diperkecil -->
      <section class="gtk-scope pt-2 pb-0 mt-6">
        <div class="row justify-content-center text-center my-sm-3">
          <div class="col-12 col-lg-10 col-xl-8">
            <h2 class="gtk-title">Data Siswa MIN 1 Rokan Hulu</h2>
            <p class="gtk-subtitle">Rekap jumlah siswa per kelas</p>
            <p class="gtk-subtitle tahun">Tahun Ajaran {{ currentYear }}/{{ nextYear }}</p>
          </div>
        </div>
      </section>

      <!-- Tabel -->
      <div class="container">
        <Table :headers="headers" :rows="rows">
        </Table>
      </div>
    </BaseLayout>
  </div>
</template>

<script setup>
import { onMounted, ref } from "vue"
import axios from "axios"

import BaseLayout from "../components/BaseLayout.vue"
import Table from "../../../examples/tables/Table.vue"
import setNavPills from "../../../assets/js/nav-pills"

const currentYear = new Date().getFullYear()
const nextYear = currentYear + 1

const headers = ["Kelas", "Jumlah Siswa", "Laki-laki", "Perempuan", "Nama Wali Kelas"]
const rows = ref([])

onMounted(async () => {
  setNavPills()
  try {
    const res = await axios.get("/landing/siswa")
    rows.value = Object.values(res.data).map(item => ({
      className: item.className,
      studentCount: item.studentCount,
      maleCount: item.maleCount,
      femaleCount: item.femaleCount,
      teacherName: item.teacherName || "-"
    }))
  } catch (e) {
    console.error("Gagal ambil data siswa:", e)
  }
})
</script>
<style scoped>
/* ====== Headings ====== */
.gtk-scope .gtk-title {
  font-weight: 800;
  color: #111827;
  letter-spacing: .2px;
  line-height: 1.2;
  margin-bottom: .25rem;
  white-space: normal;
  word-break: normal;
}

.gtk-scope .gtk-subtitle {
  color: #6b7280;
  margin: 0 auto;
  max-width: 60ch;
  font-weight: 700;
  word-break: keep-all;
}

.gtk-scope .tahun {
  font-weight: 700;
  margin-top: 4px;
}
</style>
