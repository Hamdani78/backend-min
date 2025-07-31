<template>
  <Link
    :href="href"
    class="flex items-center px-3 py-2 rounded hover:bg-gray-800"
    :class="{ 'bg-gray-800': isActive }"
  >
    <i :class="icon" class="mr-3 w-4" />
    <span>{{ label }}</span>
  </Link>
</template>

<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const props = defineProps({
  href: String,
  label: String,
  icon: String,
  exact: Boolean
})

const isActive = computed(() => {
  const current = usePage().url
  return props.exact ? current === props.href : current.startsWith(props.href)
})
</script>

