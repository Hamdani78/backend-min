<script setup>
const props = defineProps({
  label: String,
  icon: String,
  open: Boolean
})
const emit = defineEmits(['update:open'])
</script>

<template>
  <div>
    <button
      @click="emit('update:open', !open)"
      class="flex items-center justify-between w-full px-3 py-2 rounded hover:bg-gray-800"
    >
      <div class="flex items-center space-x-2">
        <i :class="icon" />
        <span>{{ label }}</span>
      </div>
      <i :class="open ? 'fas fa-chevron-down' : 'fas fa-chevron-right'" />
    </button>
    <div v-show="open" class="ml-6 mt-2 space-y-1">
      <slot />
    </div>
  </div>
</template>
