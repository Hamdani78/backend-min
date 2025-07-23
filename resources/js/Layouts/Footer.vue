<template>
  <footer class="main-footer">
    <strong>
      Copyright &copy; {{ year }}
      <a href="https://www.instagram.com/_fhmdni_/" target="_blank" rel="noopener noreferrer">_fhmdni_</a>.
    </strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.0
    </div>
  </footer>
</template>

<script setup>
const year = new Date().getFullYear()
</script>
