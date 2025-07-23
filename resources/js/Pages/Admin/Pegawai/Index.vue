<script setup>
import { usePage, router } from '@inertiajs/vue3'
import EasyDataTable from 'vue3-easy-data-table'
import 'vue3-easy-data-table/dist/style.css'
import { route } from 'ziggy-js'

// Ambil data pegawai dari props inertia
const page = usePage()
const pegawai = page.props.pegawai

// Function hapus pegawai
const deletePegawai = (id) => {
  if (confirm('Apakah Anda Yakin ?')) {
    router.delete(route('pegawai.destroy', id))
  }
}

// Columns untuk EasyDataTable
const columns = [
  { label: 'Nama', field: 'nama' },
  { label: 'NIP', field: 'nip' },
  { label: 'Email', field: 'email' },
  { label: 'Status', field: 'status' },
  { label: 'Foto', field: 'foto' },
  { label: 'Aksi', field: 'aksi' },
]

// Data rows pegawai
const rows = pegawai.map(data => ({
  ...data,
  foto: data.foto ? `/storage/pegawai/${data.foto}` : '/default-avatar.png', // default jika foto null
  aksi: null, // field dummy untuk slot aksi
}))
</script>

<template>
  <div class="content-wrapper">
    <!-- Flash message -->
    <div v-if="$page.props.flash.success" class="alert alert-primary">
      {{ $page.props.flash.success }}
    </div>

    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Data Table Pegawai</h3>
      </div>

      <div class="card-body">
        <!-- Tombol tambah -->
        <div class="mb-3">
          <a :href="route('pegawai.create')">
            <button type="button" class="btn btn-primary">
              <i class="fa fa-plus"></i> Tambah
            </button>
          </a>
        </div>

        <!-- Data table -->
        <EasyDataTable :columns="columns" :rows="rows">
          <!-- Slot foto -->
          <template #item-foto="{ foto }">
            <img :src="foto" style="width: 150px" class="img-thumbnail" />
          </template>

          <!-- Slot aksi -->
          <template #item-aksi="{ id }">
            <button @click="deletePegawai(id)" class="btn btn-outline-danger me-2">
              <i class="fa fa-trash"></i>
            </button>
            <a :href="route('pegawai.edit', id)" class="btn btn-outline-warning">
              <i class="fa fa-edit"></i>
            </a>
          </template>
        </EasyDataTable>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Style tambahan jika diperlukan */
</style>
