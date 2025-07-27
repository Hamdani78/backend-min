<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { usePage, router } from '@inertiajs/vue3'
import logo from '../Landing/assets/img/ic_logo.png'
const page = usePage()
const errors = page.props.errors || {}

const name = ref('')
const email = ref('')
const password = ref('')
const showPassword = ref(false)
const errorMessage = ref('')
const csrf = page.props.csrf_token

onMounted(() => {
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
    axios.defaults.withCredentials = true
    axios.defaults.headers.common['Accept'] = 'application/json'
})

const submitForm = async () => {
    try {
        await axios.post('/user/register', {
            name: name.value,
            email: email.value,
            password: password.value,
            _token: csrf,
        })
        router.visit('/user/login')
    } catch (error) {
        errorMessage.value = 'Registrasi gagal. Pastikan semua data benar dan email belum digunakan.'
    }
}
</script>

<template>
    <div class="min-h-screen flex">
        <!-- Logo Section -->
        <div class="hidden md:flex flex-col justify-center items-center bg-white px-4 py-5 w-1/2 rounded-br-[60px]">
            <img :src="logo" alt="Logo" class="w-full max-w-md" />
        </div>

        <!-- Register Form -->
        <div class="flex flex-col justify-center items-center bg-gray-100 w-full md:w-1/2 px-6">
            <div class="w-full max-w-md">
                <div class="text-center mb-4">
                    <h2 class="font-bold text-xl">SISTEM PPDB</h2>
                    <p class="text-gray-600">Daftar untuk membuat akun</p>
                </div>

                <div class="bg-white rounded shadow p-6">
                    <h3 class="text-center font-bold text-lg mb-4">Form Registrasi</h3>
                    <form @submit.prevent="submitForm">
                        <!-- Nama -->
                        <div class="mb-4">
                            <label class="block mb-1">Nama Lengkap</label>
                            <input v-model="name" type="text" class="w-full border rounded p-2" required />
                            <div v-if="errors.name" class="text-red-600 text-sm mt-1">{{ errors.name[0] }}</div>
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label class="block mb-1">Email</label>
                            <input v-model="email" type="email" class="w-full border rounded p-2" required />
                            <div v-if="errors.email" class="text-red-600 text-sm mt-1">{{ errors.email[0] }}</div>
                        </div>

                        <!-- Password -->
                        <div class="mb-4 relative">
                            <label class="block mb-1">Password</label>
                            <input :type="showPassword ? 'text' : 'password'" v-model="password"
                                class="w-full border rounded p-2 pr-10" required />
                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute top-8 right-3 text-gray-500">
                                <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                            <div v-if="errors.password" class="text-red-600 text-sm mt-1">{{ errors.password[0] }}</div>
                        </div>


                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full hover:bg-blue-700">
                            Daftar
                        </button>

                        <p class="text-sm text-center mt-4">
                            Sudah punya akun?
                            <a href="/user/login" class="text-blue-600 hover:underline">Masuk di sini</a>
                        </p>

                        <div v-if="errorMessage" class="text-red-600 text-sm text-center mt-2">
                            {{ errorMessage }}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
body,
html {
    height: 100%;
    margin: 0;
}
</style>
