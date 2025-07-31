<template>
  <div>
    <button
      @click="open = !open"
      class="flex items-center justify-between w-full px-3 py-2 rounded hover:bg-gray-800 text-left"
    >
      <div class="flex items-center gap-3">
        <i :class="icon" />
        <span>{{ label }}</span>
      </div>
      <i :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" />
    </button>
    <div v-show="open" class="mt-1 pl-6 space-y-1">
      <slot />
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  icon: String,
  label: String,
  open: {
    type: Boolean,
    default: false,
  },
})

const open = ref(props.open)
</script>
