<script setup>
import { onMounted } from "vue";

// Komponen Sections
import BaseLayout from "../components/BaseLayout.vue";
import Table from "../../../examples/tables/Table.vue";  

// nav-pills
import setNavPills from "../../../assets/js/nav-pills";

onMounted(() => {
  setNavPills();
});

// Data contoh untuk komponen Table
const headers = ["Kelas", "Jumlah Siswa", "Nama Wali Kelas"];
const rows = [
  {
    className: "Kelas 1A",
    studentCount: 30,
    teacherName: "Ibu Ani",
  },
  {
    className: "Kelas 1B",
    studentCount: 28,
    teacherName: "Pak Budi",
  },
  // Tambahkan lebih banyak baris sesuai kebutuhan
];
</script>

<template>
  <div>
    <BaseLayout
      title="Data Siswa"
    >
      <Table :headers="headers" :rows="rows" />  
    </BaseLayout>
  </div>
</template>
