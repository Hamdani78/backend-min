<template>
  <aside class="main-sidebar sidebar-dark-primary elevation-4 d-flex flex-column justify-content-between">
    <div>
      <Link href="/admin/dashboard" class="brand-link">
        <img :src="logo" alt="Admin" class="brand-image img-circle elevation-3" style="opacity: .8" />
        <span class="brand-text font-weight-light">ADMIN</span>
      </Link>

      <div class="sidebar">
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <Link href="/admin/dashboard" :class="['nav-link', isActive('/admin/dashboard')]">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
              </Link>
            </li>

            <li class="nav-item" :class="{ 'menu-open': isActiveStartsWith(['/pegawai','/siswa','/fasilitas','/kegiatan']) }">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-table"></i>
                <p>Tables <i class="fas fa-angle-left right"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item"><Link href="/pegawai" :class="['nav-link', isActive('/pegawai')]"><i class="far fa-circle nav-icon"></i> <p>Pegawai</p></Link></li>
                <li class="nav-item"><Link href="/siswa" :class="['nav-link', isActive('/siswa')]"><i class="far fa-circle nav-icon"></i> <p>Siswa</p></Link></li>
                <li class="nav-item"><Link href="/fasilitas" :class="['nav-link', isActive('/fasilitas')]"><i class="far fa-circle nav-icon"></i> <p>Fasilitas</p></Link></li>
                <li class="nav-item"><Link href="/kegiatan" :class="['nav-link', isActive('/kegiatan')]"><i class="far fa-circle nav-icon"></i> <p>Kegiatan</p></Link></li>
              </ul>
            </li>

            <li class="nav-item" :class="{ 'menu-open': isActiveStartsWith(['/ppdb']) }">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-user-plus"></i>
                <p>PPDB <i class="fas fa-angle-left right"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item"><Link href="/ppdb/pendaftar" :class="['nav-link', isActive('/ppdb/pendaftar')]"><i class="far fa-circle nav-icon"></i> <p>Pendaftar</p></Link></li>
                <li class="nav-item"><Link href="/ppdb/berkas" :class="['nav-link', isActive('/ppdb/berkas')]"><i class="far fa-circle nav-icon"></i> <p>Berkas</p></Link></li>
                <li class="nav-item"><Link href="/ppdb/spk" :class="['nav-link', isActive('/ppdb/spk')]"><i class="far fa-circle nav-icon"></i> <p>SPK</p></Link></li>
              </ul>
            </li>
          </ul>
        </nav>
      </div>
    </div>

    <!-- Logout pinned to bottom -->
    <div class="p-3">
      <Link href="/admin/logout" method="post" as="button" class="btn btn-block btn-danger">
        <i class="fas fa-sign-out-alt me-2"></i> Logout
      </Link>
    </div>
  </aside>
</template>

<script setup>
import { usePage, Link } from '@inertiajs/vue3'
const logo = '/admin/img/ic_logo.png'
const currentUrl = usePage().url

const isActive = (path) => currentUrl === path ? 'active' : ''
const isActiveStartsWith = (paths) => paths.some(p => currentUrl.startsWith(p))
</script>
 