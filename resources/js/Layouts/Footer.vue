<template>
  <footer class="w-full bg-gray-100 border-t text-sm text-gray-600 py-3 px-6 flex flex-col sm:flex-row items-center justify-between">
    <div>
      <span>&copy; {{ year }} </span>
      <a
        href="https://www.instagram.com/_fhmdni_/"
        target="_blank"
        rel="noopener noreferrer"
        class="text-blue-600 hover:underline font-semibold"
      >
        _fhmdni_
      </a>
      . All rights reserved.
    </div>
    <div class="mt-2 sm:mt-0">
      <span class="font-semibold">Version</span> 1.0.0
    </div>
  </footer>
</template>

<script setup>
const year = new Date().getFullYear()
</script>
