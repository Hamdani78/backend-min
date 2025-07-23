<script setup>
defineProps({
  label: String,
  value: [String, Number],
  icon: String,        // class Font Awesome, contoh: "fas fa-user-tie"
  bgColor: String      // contoh: "bg-blue-500"
})
</script>

<template>
  <div class="flex items-center border rounded p-4 shadow-sm">
    <div :class="[bgColor, 'text-white p-3 rounded mr-4']">
      <i :class="[icon, 'text-xl']"></i>
    </div>
    <div>
      <p class="text-sm text-gray-600">{{ label }}</p>
      <p class="text-xl font-bold">{{ value }}</p>
    </div>
  </div>
</template>
