<template>
  <AdminLayout title="Edit Siswa">
    <form @submit.prevent="submit" class="space-y-6 bg-white p-6 rounded shadow">
      <!-- Input Kelas -->
      <InputField
        label="Kelas"
        v-model="form.kelas"
        :error="form.errors.kelas"
      />

      <!-- Jumlah Siswa -->
      <InputField
        label="Jumlah Siswa"
        type="number"
        v-model="form.jml_siswa"
        :error="form.errors.jml_siswa"
      />

      <!-- Jumlah Laki-laki -->
      <InputField
        label="Jumlah Laki-laki"
        type="number"
        v-model="form.laki_laki"
        :error="form.errors.laki_laki"
      />

      <!-- Jumlah Perempuan -->
      <InputField
        label="Jumlah Perempuan"
        type="number"
        v-model="form.perempuan"
        :error="form.errors.perempuan"
      />

      <!-- Wali Kelas -->
      <div>
        <label class="block font-medium text-sm text-gray-700 mb-1">Wali Kelas</label>
        <select
          v-model="form.pegawai_id"
          class="form-select w-full border-gray-300 rounded"
        >
          <option disabled value="">Pilih Pegawai</option>
          <option v-for="item in pegawai" :key="item.id" :value="item.id">
            {{ item.nama }}
          </option>
        </select>
        <div v-if="form.errors.pegawai_id" class="text-red-500 text-sm mt-1">
          {{ form.errors.pegawai_id }}
        </div>
      </div>

      <!-- Tombol Submit -->
      <button
        type="submit"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded disabled:opacity-50"
        :disabled="form.processing"
      >
        Update
      </button>
    </form>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputField from '@/Components/InputField.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({ siswa: Object, pegawai: Array });

const form = useForm({
  kelas: props.siswa.kelas,
  jml_siswa: props.siswa.jml_siswa,
  laki_laki: props.siswa.laki_laki,
  perempuan: props.siswa.perempuan,
  pegawai_id: props.siswa.pegawai_id,
});

function submit() {
  form.put(route('siswa.update', props.siswa.id));
}
</script>
