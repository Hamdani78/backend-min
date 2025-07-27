<template>
  <UserLayout>
    <div class="p-4">
      <h1 class="text-2xl font-bold">Dashboard Pendaftar</h1>
      <p>Selamat datang, {{ user.name }}</p>
    </div>
  </UserLayout>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3'
import UserLayout from './UserLayouts/UserLayout.vue'

const user = usePage().props.auth.user
</script>
