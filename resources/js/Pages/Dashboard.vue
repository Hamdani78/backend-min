<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

defineOptions({
  layout: AdminLayout
})

const { props } = usePage()
const userName = computed(() => props.auth?.user?.name ?? 'Admin')
</script>

<template>
  <Head title="Dashboard" />

  <div class="p-4">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Selamat datang, {{ userName }}</h5>
        <p class="card-text">Anda sudah berhasil login.</p>
      </div>
    </div>
  </div>
</template>