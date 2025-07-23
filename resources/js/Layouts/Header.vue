<template>
  <nav class="main-header navbar navbar-expand navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button">
          <i class="fas fa-bars"></i>
        </a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <Link href="/admin/dashboard" class="nav-link">Home</Link>
      </li>
    </ul>

    <ul class="navbar-nav ms-auto">
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" data-bs-toggle="dropdown" href="#">
          <i class="fas fa-user-circle"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-end">
          <span class="dropdown-item-text">Admin</span>
          <div class="dropdown-divider"></div>
          <Link href="/admin/logout" method="post" as="button" class="dropdown-item">Logout</Link>
        </div>
      </li>
    </ul>
  </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>