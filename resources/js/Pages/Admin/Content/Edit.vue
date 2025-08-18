<!-- resources/js/Pages/Admin/Content/Edit.vue -->
<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, useForm, usePage } from '@inertiajs/vue3'

const props = defineProps({
  content: { type: Object, required: true }
})

const form = useForm({
  title: props.content.title ?? '',
  body:  props.content.body  ?? '',
})

const submit = () => {
  // GUNAKAN content.update (tanpa prefix admin.)
  form.put(route('content.update', props.content.id), {
    preserveScroll: true
  })
}

const page = usePage()
</script>

<template>
  <AdminLayout title="Edit Konten">
    <div class="mb-4">
      <!-- GUNAKAN content.index (tanpa prefix admin.) -->
      <Link :href="route('content.index')" class="text-sm text-indigo-600 hover:underline">← Kembali</Link>
    </div>

    <!-- Flash -->
    <div
      v-if="page.props.flash?.success"
      class="bg-green-50 text-green-700 border border-green-200 px-4 py-2 rounded mb-4"
    >
      {{ page.props.flash.success }}
    </div>

    <div class="bg-white rounded shadow p-4">
      <h1 class="text-lg font-semibold mb-4">Edit Konten</h1>

      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block text-sm font-medium mb-1">Slug</label>
          <input
            type="text"
            :value="content.slug"
            disabled
            class="w-full border rounded px-3 py-2 bg-gray-50 text-gray-600 cursor-not-allowed"
          />
          <p class="text-xs text-gray-500 mt-1">
            Slug dipakai untuk mapping halaman (contoh:
            <code>kamad</code>, <code>visimisi</code>, <code>sejarah</code>).
          </p>
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
          <p class="text-xs text-gray-500 mt-1">
            Dukungan HTML diperbolehkan. Render di frontend menggunakan <code>v-html</code>.
          </p>
        </div>

        <div class="flex items-center gap-2">
          <button
            type="submit"
            :disabled="form.processing"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded disabled:opacity-60"
          >
            {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
          </button>

          <Link :href="route('content.index')" class="px-4 py-2 rounded border">
            Batal
          </Link>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>
