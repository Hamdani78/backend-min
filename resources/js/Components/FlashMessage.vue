<template>
  <div v-if="message" class="bg-blue-100 text-blue-800 p-4 rounded mb-4 border border-blue-300">
    {{ message }}
  </div>
  <div v-if="error" class="bg-red-100 text-red-800 p-4 rounded mb-4 border border-red-300">
    {{ error }}
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()

const message = computed(() => page.props.value.flash?.success)
const error = computed(() => page.props.value.flash?.error)
</script>
