<script setup>
defineProps({
  headers: {
    type: Array,
    default: () => ["Kelas", "Jumlah Siswa", "Laki-laki", "Perempuan", "Nama Wali Kelas"],
  },
  rows: {
    type: Array,
    default: () => [],
  },
})
</script>

<template>
  <section class="pt-3 mt-3">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-12">
          <div class="card">
            <div class="table-responsive">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th v-for="(header, index) in headers" :key="index"
                      class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                      {{ header }}
                    </th>
                  </tr>
                </thead>
                <tbody v-if="rows.length > 0">
                  <tr v-for="(row, index) in rows" :key="index">
                    <td class="text-center">{{ row.className }}</td>
                    <td class="text-center">{{ row.studentCount }}</td>
                    <td class="text-center">{{ row.maleCount }}</td>
                    <td class="text-center">{{ row.femaleCount }}</td>
                    <td class="text-center">{{ row.teacherName }}</td>
                  </tr>
                </tbody>
                <tbody v-else>
                  <tr>
                    <td colspan="5" class="text-center py-3 text-muted">
                      Tidak ada data siswa tersedia.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
