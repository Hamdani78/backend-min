<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const props = defineProps({
  href: String,
  label: String,
  icon: String,
  exact: Boolean
})

const page = usePage()
const isActive = computed(() => {
  return props.exact
    ? page.url === props.href
    : page.url.startsWith(props.href)
})
</script>

<template>
  <Link
    :href="href"
    class="flex items-center px-3 py-2 rounded transition"
    :class="isActive ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'"
  >
    <i v-if="icon" :class="[icon, 'mr-2']" />
    <span>{{ label }}</span>
  </Link>
</template>
