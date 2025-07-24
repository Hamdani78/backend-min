<script setup>
defineProps({
  headers: {
    type: Array,
    default: () => ["Kelas", "Jumlah Siswa", "Nama Wali Kelas"],
  },
  rows: {
    type: Array,
    required: true,
    className: String, 
    studentCount: Number,  
    teacherName: String,  
  },
});
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
                    <th
                      v-for="(header, index) in headers"
                      :key="header"
                      class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                      :class="{ 'ps-2': index == 1, 'text-center': index > 1 }"
                    >
                      {{ header }}
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="(
                      {
                        className,
                        studentCount,
                        teacherName,
                      },
                      index
                    ) of rows"
                    :key="index"
                  >
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-xs">{{ className }}</h6>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0">{{ studentCount }}</p>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <span class="text-secondary text-xs font-weight-bold">{{ teacherName }}</span>
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
