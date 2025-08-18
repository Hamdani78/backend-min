<!-- resources/js/Pages/Admin/Content/Create.vue -->
<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, useForm, usePage } from '@inertiajs/vue3'

const form = useForm({
  slug: '',
  title: '',
  body: ''
})

const submit = () => {
  form.post(route('content.store'), { preserveScroll: true })
}

const page = usePage()
</script>

<template>
  <AdminLayout title="Tambah Konten">
    <div class="mb-4">
      <Link :href="route('content.index')" class="text-sm text-indigo-600 hover:underline">← Kembali</Link>
    </div>

    <div v-if="page.props.flash?.success" class="bg-blue-100 text-blue-800 p-4 rounded mb-4">
      {{ page.props.flash.success }}
    </div>

    <div class="bg-white p-6 rounded shadow">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold">Tambah Konten</h2>
      </div>

      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block text-sm font-medium mb-1">Slug</label>
          <input
            v-model="form.slug"
            type="text"
            placeholder="contoh: kamad / visimisi / sejarah / pengumuman"
            class="w-full border rounded px-3 py-2"
            :class="{'border-red-500': form.errors.slug}"
          />
          <p v-if="form.errors.slug" class="text-red-600 text-sm mt-1">{{ form.errors.slug }}</p>
          <p class="text-xs text-gray-500 mt-1">Huruf kecil, angka, dan tanda minus (-) saja.</p>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Judul</label>
          <input
            v-model="form.title"
            type="text"
            class="w-full border rounded px-3 py-2"
            :class="{'border-red-500': form.errors.title}"
          />
          <p v-if="form.errors.title" class="text-red-600 text-sm mt-1">{{ form.errors.title }}</p>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Isi Konten</label>
          <textarea
            v-model="form.body"
            class="w-full border rounded px-3 py-2 min-h-[240px]"
            :class="{'border-red-500': form.errors.body}"
          />
          <p v-if="form.errors.body" class="text-red-600 text-sm mt-1">{{ form.errors.body }}</p>
          <p class="text-xs text-gray-500 mt-1">Bisa HTML, nanti ditampilkan dengan <code>v-html</code>.</p>
        </div>

        <div class="flex items-center gap-2">
          <button
            type="submit"
            :disabled="form.processing"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded disabled:opacity-60"
          >
            {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
          </button>

          <Link :href="route('content.index')" class="px-4 py-2 rounded border">Batal</Link>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>
