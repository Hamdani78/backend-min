<template>
  <div>
    <BaseLayout title="Data Siswa">
      <Table :headers="headers" :rows="rows" />
    </BaseLayout>
  </div>
</template>

<script setup>
import { onMounted, ref } from "vue"
import axios from "axios"

import BaseLayout from "../components/BaseLayout.vue"
import Table from "../../../examples/tables/Table.vue"
import setNavPills from "../../../assets/js/nav-pills"

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
      teacherName: item.teacherName,
    }))

    console.log("Data yang dikirim ke Table:", rows.value)

  } catch (error) {
    console.error("Gagal ambil data siswa:", error)
  }
})
</script>
