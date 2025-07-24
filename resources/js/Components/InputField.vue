<template>
  <div>
    <label class="block text-sm font-medium text-gray-700 mb-1">{{ label }}</label>
    <input
      :type="type"
      v-model="inputValue"
      @input="$emit('update:modelValue', $event.target.value)"
      class="form-input w-full rounded border-gray-300"
    />
    <div v-if="error" class="text-sm text-red-600 mt-1">
      {{ error }}
    </div>
  </div>
</template>

<script setup>
defineProps({
  label: String,
  type: { type: String, default: 'text' },
  modelValue: [String, Number],
  error: String,
})

defineEmits(['update:modelValue'])

const inputValue = defineModel()
</script>
